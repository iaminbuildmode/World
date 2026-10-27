#!/usr/bin/env bash
set -euo pipefail
TARGET="/var/lib/docker/volumes/world_wp_data/_data/wp-content/plugins/woocommerce/vendor/wordpress/mcp-adapter/includes/Transport/Infrastructure/RequestRouter.php"
if grep -q "resources/templates/list" "$TARGET"; then
  echo "Patch already applied."
  exit 0
fi
python3 - "$TARGET" <<'PY'
import sys
p=sys.argv[1]
s=open(p).read()
old="\t\t\t'resources/list'      => fn() => $this->add_cursor_compatibility( $this->context->resources_handler->list_resources( $request_id ) ),\n"
new=old+"\t\t\t'resources/templates/list' => fn() => array( 'resourceTemplates' => array(), '_metadata' => array( 'component_type' => 'resources', 'templates_count' => 0 ) ),\n"
assert old in s
s=s.replace(old,new,1)
open(p,"w").write(s)
print("patched")
PY
