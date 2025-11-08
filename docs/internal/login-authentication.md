# Fluxo de Login por Plataforma

As requisições de login agora possuem rotas separadas para controlar quando uma sessão HTTP será criada. O cabeçalho `X-Client` continua obrigatório e deve corresponder às plataformas listadas em `config/app.php`.

## Rotas Disponíveis

| Rota         | Middleware                 | Plataforma (`X-Client`) | Sessão | Token |
|--------------|----------------------------|--------------------------|--------|-------|
| `POST /login`     | `ensureClientHeader`            | `mobile` ou `desktop`     | Não cria sessão. Cookies de sessão enviados são descartados. | Retorna token Sanctum nomeado com o valor do cabeçalho. |
| `POST /login/web` | `web`, `ensureClientHeader`     | `spa`                     | Requer sessão. Regenera o ID da sessão na autenticação. | Não retorna token. |

## Observações

- O middleware `EnsureClientHeaderMiddleware` valida a compatibilidade entre a rota acessada e o valor de `X-Client`. Solicitações usando combinações inválidas respondem com `Invalid client platform.` (HTTP 401).
- Clientes stateless (`mobile` e `desktop`) podem enviar cookies de sessão antigos; o middleware os remove da requisição e solicita a expiração no response para evitar a criação de sessão.
- Tokens Sanctum existentes com o mesmo nome da plataforma são revogados antes da emissão de um novo token.
- O fluxo de login do SPA (`/login/web`) mantém o comportamento anterior de autenticação baseada em sessão e retorno dos dados do usuário autenticado.
