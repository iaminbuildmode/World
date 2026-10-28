# MCP (WordPress / WooCommerce)

- Endpoint: https://world.leb.as/wp-json/mcp/mcp-adapter-default-server
- Auth: Basic Auth (Application Password)
- Username: aminshurabi
- Application Password: (stored securely in OpenCode config / system; not committed)

OpenCode global config: ~/.config/opencode/opencode.json
MCP server name: wordpress

## Auto-start after reboot

- `mcp-patch.service` (systemd) re-applies the MCP patch after boot.
- OpenCode service and Docker containers are enabled for auto-start.

## Elementor MCP
- Server name: lebas-elementor
- Endpoint: https://world.leb.as/wp-json/elementor/mcp/
- Config file: ~/.config/opencode/opencode.json and ~/mcp-config.json
