## 2025-05-18 - Telegram WebApp initData Replay Vulnerability
**Vulnerability:** Telegram WebApp `initData` validation in `FaoximaAuth::validateInitData` validated HMAC signatures but did not verify the `auth_date` timestamp freshness.
**Learning:** HMAC signature validation proves the data originated from Telegram, but without `auth_date` validation, captured auth payloads could be replayed indefinitely to authenticate as target users.
**Prevention:** Always check `auth_date` timestamp freshness (e.g. `time() - auth_date > 86400`) during `initData` validation in Telegram WebApp authentication flows.
