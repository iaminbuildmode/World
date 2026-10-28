# MCP (WordPress / WooCommerce)

- Endpoint: https://world.leb.as/wp-json/mcp/mcp-adapter-default-server
- Auth: Basic Auth (Application Password)
- Username: aminshurabi
- Application Password: (stored securely in OpenCode & Antigravity configs; not committed)

OpenCode global config: ~/.config/opencode/opencode.json
Antigravity global config: ~/.gemini/config/mcp_config.json (and ~/mcp-config.json)
MCP server name: wordpress

## Auto-start after reboot

- `mcp-patch.service` (systemd) re-applies the MCP patch after boot.
- OpenCode service and Docker containers are enabled for auto-start.

## Elementor MCP
- Server name: lebas-elementor (or `%d9%84%d8%a8%d8%a7%d8%b3-elementor`)
- Endpoint: https://world.leb.as/wp-json/elementor/mcp/
- Config file: ~/.config/opencode/opencode.json, ~/.gemini/config/mcp_config.json, and ~/mcp-config.json
