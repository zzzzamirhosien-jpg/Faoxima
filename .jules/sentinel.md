## 2026-09-21 - Telegram Mini App InitData Replay Protection
**Vulnerability:** Telegram WebApp `initData` payload authentication in `api/lib/Auth.php` (`FaoximaAuth::validateInitData`) verified HMAC signatures but did not check `auth_date` freshness, allowing stolen raw `initData` query strings to be replayed indefinitely.
**Learning:** `FaoximaAuth::validateContactResponse` had anti-replay `auth_date` expiration logic, but `FaoximaAuth::validateInitData` lacked `auth_date` verification altogether.
**Prevention:** Always verify `auth_date` timestamp freshness (e.g. `<= 86400` seconds) when authenticating Telegram Mini App `initData` payloads.
