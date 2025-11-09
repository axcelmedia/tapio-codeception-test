<?php
namespace Tests\Support\Helper;
use Codeception\Module;

class Acceptance extends Module
{
    // Reusable login helper
    public function loginAs(\AcceptanceTester $I, string $role = 'test_user')
    {
        $users = include codecept_data_dir() . 'users.php';
        if (!isset($users[$role])) {
            throw new \InvalidArgumentException("User role '{$role}' not found in data file.");
        }
        $email = $users[$role]['email'];
        $pass  = $users[$role]['pass'];

        $I->amOnPage('/auth/login');
        // preferred selectors (data-qa)
        $emailSel = 'input[data-qa="login-email"]';
        $passSel  = 'input[data-qa="login-password"]';
        $submit   = 'button[data-qa="login-submit"]';

        // Fallbacks:
        if (!$this->seeElementInDom($emailSel)) $emailSel = '#loginform-email';
        if (!$this->seeElementInDom($passSel)) $passSel = '#loginform-password';
        if (!$this->seeElementInDom($submit)) $submit = 'button[name="login-button"]';

        $I->fillField($emailSel, $email);
        $I->fillField($passSel, $pass);
        $I->waitForElementVisible($submit, 5);
        $I->click($submit);

        // Post-login check: prefer data-qa profile element
        $I->waitForText('Dashboard', 10) || $I->seeElement('a[data-qa="nav-logout"]') || $I->see('Sign Out');
    }

    // small DOM-check helper for PhpBrowser
    public function seeElementInDom(string $needle): bool
    {
        $content = $this->getModule('PhpBrowser')->_getResponseContent();
        return strpos($content, $needle) !== false;
    }

    // Called on failure to save last response for debugging
    public function _failed(\Codeception\TestInterface $test, $fail)
    {
        $resp = $this->getModule('PhpBrowser')->_getResponseContent();
        $dir = codecept_output_dir();
        file_put_contents($dir . 'last_response.html', $resp);
    }

    /**
     * Count product links containing a specific search term
     *
     * @param string $searchTerm Term to search for in anchor text (case-insensitive)
     * @return int Count of matching product links
     */
    public function countProductLinksContaining(string $searchTerm): int
    {
        $phpBrowser = $this->getModule('PhpBrowser');
        $productLinks = $phpBrowser->grabMultiple('a');
        $linksContainingTerm = array_filter($productLinks, function($text) use ($searchTerm) {
            return stripos($text, $searchTerm) !== false;
        });
        return count($linksContainingTerm);
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
