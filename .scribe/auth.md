# Authenticating requests

To authenticate requests, include an **`Authorization`** header with the value **`"Bearer Bearer {YOUR_ACCESS_TOKEN}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

Для получения токена доступа используйте endpoint `/api/v1/auth/login`. После успешного входа вы получите `access_token` и `refresh_token`. Используйте `access_token` в заголовке `Authorization: Bearer {access_token}` для доступа к защищённым эндпоинтам.
