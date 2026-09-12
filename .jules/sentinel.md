## 2026-03-31 - CSRF Token Validation Positioned After Action Handlers
**Vulnerability:** CSRF token verification block in `panel/user.php` was placed after the GET action handlers, which executed `exit;` upon completion.
**Learning:** Action handlers in PHP endpoints that issue redirects or call `exit;` will bypass any validation logic placed after them.
**Prevention:** Always place security checks (authentication, authorization, CSRF token validation, rate limiting) at the very top of request handling before any state-changing operations or action handlers execute.
