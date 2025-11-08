<?php
namespace Support\Helper;
use Codeception\Module;

class Acceptance extends Module
{
    // Reusable login helper
    public function loginAs(string $role = 'test_user')
    {
        $users = include codecept_data_dir() . 'users.php';
        if (!isset($users[$role])) {
            throw new \InvalidArgumentException("User role '{$role}' not found in data file.");
        }
        $email = $users[$role]['email'];
        $pass  = $users[$role]['pass'];

        $browser = $this->getModule('PhpBrowser');
        $browser->amOnPage('/auth/login');

        // Use fallback selectors (the page uses IDs, not data-qa)
        $emailSel = '#loginform-email';
        $passSel  = '#loginform-password';
        $submit   = 'button[name="login-button"]';

        $browser->fillField($emailSel, $email);
        $browser->fillField($passSel, $pass);
        $browser->click($submit);

        // Login action complete - let the test verify success
    }

    // Reusable company search helper
    public function searchCompany(string $companyName)
    {
        $browser = $this->getModule('PhpBrowser');

        // Navigate to companies page
        $browser->amOnPage('/admin/company');

        // Fill in the search field
        $browser->fillField('#companysearch-name', $companyName);

        // Submit the search
        $browser->click('button[type="submit"]');
    }

    // Reusable logout helper - resets session cookies
    public function logout()
    {
        $browser = $this->getModule('PhpBrowser');

        // Reset session cookies to clear authentication
        $browser->resetCookie('PHPSESSID');
        $browser->resetCookie('_csrf');
        $browser->resetCookie('_identity');
    }

    // Called on failure to save last response for debugging
    public function _failed(\Codeception\TestInterface $test, $fail)
    {
        try {
            $resp = $this->getModule('PhpBrowser')->_getResponseContent();
            $dir = codecept_output_dir();
            file_put_contents($dir . 'last_response.html', $resp);
        } catch (\Exception $e) {
            // Silently fail if no page was loaded
        }
    }

    // Optional: wrapper to get IMAP reset link (keep simple)
    public function fetchResetLinkFromImap($searchPattern = '/reset/i', $timeout = 60)
    {
        $host = getenv('IMAP_HOST');
        $port = getenv('IMAP_PORT') ?: 993;
        $user = getenv('IMAP_USER');
        $pass = getenv('IMAP_PASS');
        $flags = getenv('IMAP_FLAGS') ?: '/imap/ssl/novalidate-cert';

        if (!$host || !$user) return null;
        $mailbox = sprintf('{%s:%d%s}', $host, $port, $flags);
        $start = time();
        while ((time() - $start) < $timeout) {
            $inbox = @imap_open($mailbox, $user, $pass);
            if (!$inbox) {
                sleep(3);
                continue;
            }
            $emails = @imap_search($inbox, 'UNSEEN');
            if ($emails) {
                rsort($emails);
                foreach ($emails as $msgno) {
                    $body = imap_fetchbody($inbox, $msgno, 1.2);
                    if (empty($body)) $body = imap_fetchbody($inbox, $msgno, 1);
                    if (preg_match('/https?:\/\/[^\s"]+/', $body, $m)) {
                        imap_close($inbox);
                        return $m[0];
                    }
                }
            }
            imap_close($inbox);
            sleep(3);
        }
        return null;
    }
}
