<?php
/**
 * Plugin Name: MCP WordPress Abilities
 * Description: Exposes common WordPress settings/content/taxonomy operations as public abilities for MCP.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_abilities_api_categories_init', function () {
    wp_register_ability_category( 'wordpress-management', array(
        'label'       => 'WordPress Management',
        'description' => 'Abilities for managing WordPress settings, content, and taxonomies.',
    ) );
} );

add_action( 'wp_abilities_api_init', function () {

    $cap_admin = function () {
        return current_user_can( 'manage_options' );
    };

    // --- Options ---
    wp_register_ability( 'wordpress/get-option', array(
        'label'               => 'Get Option',
        'description'         => 'Get a WordPress option value by name.',
        'category'            => 'wordpress-management',
        'input_schema'        => array(
            'type'       => 'object',
            'properties' => array(
                'name' => array( 'type' => 'string', 'description' => 'Option name.' ),
            ),
            'required' => array( 'name' ),
        ),
        'output_schema'       => array( 'type' => 'mixed' ),
        'execute_callback'    => function ( $input ) {
            $name = sanitize_key( $input['name'] ?? '' );
            if ( ! $name ) {
                return new WP_Error( 'invalid_option', 'Option name is required.' );
            }
            return get_option( $name );
        },
        'permission_callback' => $cap_admin,
        'meta'                => array( 'public' => true, 'mcp' => array( 'public' => true, 'type' => 'tool' ) ),
    ) );

    wp_register_ability( 'wordpress/update-option', array(
        'label'               => 'Update Option',
        'description'         => 'Update a WordPress option value by name.',
        'category'            => 'wordpress-management',
        'input_schema'        => array(
            'type'       => 'object',
            'properties' => array(
                'name'  => array( 'type' => 'string', 'description' => 'Option name.' ),
                'value' => array( 'description' => 'New value (any type).' ),
            ),
            'required' => array( 'name', 'value' ),
        ),
        'output_schema'       => array( 'type' => 'boolean' ),
        'execute_callback'    => function ( $input ) {
            $name = sanitize_key( $input['name'] ?? '' );
            if ( ! $name ) {
                return new WP_Error( 'invalid_option', 'Option name is required.' );
            }
            return update_option( $name, $input['value'] );
        },
        'permission_callback' => $cap_admin,
        'meta'                => array( 'public' => true, 'mcp' => array( 'public' => true, 'type' => 'tool' ) ),
    ) );

    // --- Pages ---
    wp_register_ability( 'wordpress/list-pages', array(
        'label'               => 'List Pages',
        'description'         => 'List WordPress pages.',
        'category'            => 'wordpress-management',
        'input_schema'        => array(
            'type'       => 'object',
            'properties' => array(
                'number' => array( 'type' => 'integer', 'description' => 'Max number of pages.' ),
                'search' => array( 'type' => 'string', 'description' => 'Search term.' ),
            ),
        ),
        'output_schema'       => array( 'type' => 'array' ),
        'execute_callback'    => function ( $input ) {
            $args = array(
                'post_type'      => 'page',
                'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
                'posts_per_page' => isset( $input['number'] ) ? (int) $input['number'] : 50,
                's'              => $input['search'] ?? '',
            );
            $q = new WP_Query( $args );
            $out = array();
            foreach ( $q->posts as $p ) {
                $out[] = array(
                    'id'     => $p->ID,
                    'title'  => $p->post_title,
                    'slug'   => $p->post_name,
                    'status' => $p->post_status,
                );
            }
            return $out;
        },
        'permission_callback' => $cap_admin,
        'meta'                => array( 'public' => true, 'mcp' => array( 'public' => true, 'type' => 'tool' ) ),
    ) );

    wp_register_ability( 'wordpress/update-page', array(
        'label'               => 'Update Page',
        'description'         => 'Update a WordPress page by ID (title, content, status, slug).',
        'category'            => 'wordpress-management',
        'input_schema'        => array(
            'type'       => 'object',
            'properties' => array(
                'id'      => array( 'type' => 'integer', 'description' => 'Page ID.' ),
                'title'   => array( 'type' => 'string' ),
                'content' => array( 'type' => 'string' ),
                'status'  => array( 'type' => 'string', 'enum' => array( 'publish', 'draft', 'pending', 'private' ) ),
                'slug'    => array( 'type' => 'string' ),
            ),
            'required' => array( 'id' ),
        ),
        'output_schema'       => array( 'type' => 'object' ),
        'execute_callback'    => function ( $input ) {
            $id = (int) ( $input['id'] ?? 0 );
            if ( ! $id ) {
                return new WP_Error( 'invalid_id', 'Page ID required.' );
            }
            $data = array( 'ID' => $id );
            if ( isset( $input['title'] ) )   $data['post_title']   = $input['title'];
            if ( isset( $input['content'] ) ) $data['post_content'] = $input['content'];
            if ( isset( $input['status'] ) )  $data['post_status']  = $input['status'];
            if ( isset( $input['slug'] ) )    $data['post_name']    = $input['slug'];
            $res = wp_update_post( $data, true );
            if ( is_wp_error( $res ) ) return $res;
            return array( 'id' => $res, 'updated' => true );
        },
        'permission_callback' => $cap_admin,
        'meta'                => array( 'public' => true, 'mcp' => array( 'public' => true, 'type' => 'tool' ) ),
    ) );

    wp_register_ability( 'wordpress/create-page', array(
        'label'               => 'Create Page',
        'description'         => 'Create a new WordPress page.',
        'category'            => 'wordpress-management',
        'input_schema'        => array(
            'type'       => 'object',
            'properties' => array(
                'title'   => array( 'type' => 'string' ),
                'content' => array( 'type' => 'string' ),
                'status'  => array( 'type' => 'string', 'enum' => array( 'publish', 'draft', 'pending', 'private' ) ),
                'slug'    => array( 'type' => 'string' ),
            ),
            'required' => array( 'title' ),
        ),
        'output_schema'       => array( 'type' => 'object' ),
        'execute_callback'    => function ( $input ) {
            $data = array(
                'post_type'    => 'page',
                'post_title'   => $input['title'],
                'post_content' => $input['content'] ?? '',
                'post_status'  => $input['status'] ?? 'draft',
                'post_name'    => $input['slug'] ?? '',
            );
            $id = wp_insert_post( $data, true );
            if ( is_wp_error( $id ) ) return $id;
            return array( 'id' => $id, 'created' => true );
        },
        'permission_callback' => $cap_admin,
        'meta'                => array( 'public' => true, 'mcp' => array( 'public' => true, 'type' => 'tool' ) ),
    ) );

    // --- Taxonomies (categories/tags) ---
    foreach ( array( 'category', 'post_tag' ) as $tax ) {
        $label   = $tax === 'category' ? 'Category' : 'Tag';
        $slug    = $tax === 'category' ? 'category' : 'tag';
        $plural  = $tax === 'category' ? 'categories' : 'tags';
        $labelp  = $tax === 'category' ? 'Categories' : 'Tags';

        wp_register_ability( "wordpress/list-{$plural}", array(
            'label'               => "List {$labelp}",
            'description'         => "List {$labelp}.",
            'category'            => 'wordpress-management',
            'input_schema'        => array(
                'type'       => 'object',
                'properties' => array(
                    'number' => array( 'type' => 'integer' ),
                    'search' => array( 'type' => 'string' ),
                ),
            ),
            'output_schema'       => array( 'type' => 'array' ),
            'execute_callback'    => function ( $input ) use ( $tax ) {
                $terms = get_terms( array(
                    'taxonomy'   => $tax,
                    'number'     => isset( $input['number'] ) ? (int) $input['number'] : 100,
                    'search'     => $input['search'] ?? '',
                    'hide_empty' => false,
                ) );
                if ( is_wp_error( $terms ) ) return $terms;
                $out = array();
                foreach ( $terms as $t ) {
                    $out[] = array( 'id' => $t->term_id, 'name' => $t->name, 'slug' => $t->slug, 'count' => $t->count );
                }
                return $out;
            },
            'permission_callback' => $cap_admin,
            'meta'                => array( 'public' => true, 'mcp' => array( 'public' => true, 'type' => 'tool' ) ),
        ) );

        wp_register_ability( "wordpress/create-{$slug}", array(
            'label'               => "Create {$label}",
            'description'         => "Create a new {$label}.",
            'category'            => 'wordpress-management',
            'input_schema'        => array(
                'type'       => 'object',
                'properties' => array(
                    'name' => array( 'type' => 'string' ),
                    'slug' => array( 'type' => 'string' ),
                ),
                'required' => array( 'name' ),
            ),
            'output_schema'       => array( 'type' => 'object' ),
            'execute_callback'    => function ( $input ) use ( $tax ) {
                $res = wp_insert_term( $input['name'], $tax, array( 'slug' => $input['slug'] ?? '' ) );
                if ( is_wp_error( $res ) ) return $res;
                return array( 'id' => $res['term_id'], 'created' => true );
            },
            'permission_callback' => $cap_admin,
            'meta'                => array( 'public' => true, 'mcp' => array( 'public' => true, 'type' => 'tool' ) ),
        ) );

        wp_register_ability( "wordpress/update-{$slug}", array(
            'label'               => "Update {$label}",
            'description'         => "Update a {$label} by ID.",
            'category'            => 'wordpress-management',
            'input_schema'        => array(
                'type'       => 'object',
                'properties' => array(
                    'id'   => array( 'type' => 'integer' ),
                    'name' => array( 'type' => 'string' ),
                    'slug' => array( 'type' => 'string' ),
                ),
                'required' => array( 'id' ),
            ),
            'output_schema'       => array( 'type' => 'object' ),
            'execute_callback'    => function ( $input ) use ( $tax ) {
                $id = (int) ( $input['id'] ?? 0 );
                if ( ! $id ) return new WP_Error( 'invalid_id', 'Term ID required.' );
                $data = array();
                if ( isset( $input['name'] ) ) $data['name'] = $input['name'];
                if ( isset( $input['slug'] ) ) $data['slug'] = $input['slug'];
                $res = wp_update_term( $id, $tax, $data );
                if ( is_wp_error( $res ) ) return $res;
                return array( 'id' => $id, 'updated' => true );
            },
            'permission_callback' => $cap_admin,
            'meta'                => array( 'public' => true, 'mcp' => array( 'public' => true, 'type' => 'tool' ) ),
        ) );

        wp_register_ability( "wordpress/delete-{$slug}", array(
            'label'               => "Delete {$label}",
            'description'         => "Delete a {$label} by ID.",
            'category'            => 'wordpress-management',
            'input_schema'        => array(
                'type'       => 'object',
                'properties' => array(
                    'id' => array( 'type' => 'integer' ),
                ),
                'required' => array( 'id' ),
            ),
            'output_schema'       => array( 'type' => 'boolean' ),
            'execute_callback'    => function ( $input ) use ( $tax ) {
                $id = (int) ( $input['id'] ?? 0 );
                if ( ! $id ) return new WP_Error( 'invalid_id', 'Term ID required.' );
                $res = wp_delete_term( $id, $tax );
                if ( is_wp_error( $res ) ) return $res;
                return (bool) $res;
            },
            'permission_callback' => $cap_admin,
            'meta'                => array( 'public' => true, 'mcp' => array( 'public' => true, 'type' => 'tool' ) ),
        ) );
    }

    // --- 404 page helper: update page with slug "404" or create it ---
    wp_register_ability( 'wordpress/update-404-page', array(
        'label'               => 'Update 404 Page',
        'description'         => 'Update or create a page used as 404 content (by slug 404).',
        'category'            => 'wordpress-management',
        'input_schema'        => array(
            'type'       => 'object',
            'properties' => array(
                'title'   => array( 'type' => 'string' ),
                'content' => array( 'type' => 'string' ),
                'status'  => array( 'type' => 'string', 'enum' => array( 'publish', 'draft', 'pending', 'private' ) ),
            ),
        ),
        'output_schema'       => array( 'type' => 'object' ),
        'execute_callback'    => function ( $input ) {
            $page = get_page_by_path( '404' );
            if ( $page ) {
                $data = array( 'ID' => $page->ID );
                if ( isset( $input['title'] ) )   $data['post_title']   = $input['title'];
                if ( isset( $input['content'] ) ) $data['post_content'] = $input['content'];
                if ( isset( $input['status'] ) )  $data['post_status']  = $input['status'];
                $res = wp_update_post( $data, true );
                if ( is_wp_error( $res ) ) return $res;
                return array( 'id' => $res, 'updated' => true );
            }
            $id = wp_insert_post( array(
                'post_type'    => 'page',
                'post_title'   => $input['title'] ?? '404',
                'post_name'    => '404',
                'post_content' => $input['content'] ?? '',
                'post_status'  => $input['status'] ?? 'publish',
            ), true );
            if ( is_wp_error( $id ) ) return $id;
            return array( 'id' => $id, 'created' => true );
        },
        'permission_callback' => $cap_admin,
        'meta'                => array( 'public' => true, 'mcp' => array( 'public' => true, 'type' => 'tool' ) ),
    ) );

} );

// Additional abilities (content, media, WooCommerce)
add_action( 'wp_abilities_api_init', function () {

    $cap_admin = function () { return current_user_can( 'manage_options' ); };
    $cap_shop  = function () { return current_user_can( 'manage_woocommerce' ); };

    // --- Page get/delete ---
    wp_register_ability( 'wordpress/page-get', array(
        'label' => 'Get Page',
        'description' => 'Read a WordPress page/post by ID.',
        'category' => 'wordpress-management',
        'input_schema' => array( 'type'=>'object', 'properties'=>array('id'=>array('type'=>'integer')), 'required'=>array('id') ),
        'output_schema' => array( 'type'=>'object' ),
        'execute_callback' => function ( $input ) {
            $p = get_post( (int) $input['id'] );
            if ( ! $p ) return new WP_Error( 'not_found', 'Post not found.' );
            return array( 'id'=>$p->ID, 'title'=>$p->post_title, 'content'=>$p->post_content, 'status'=>$p->post_status, 'slug'=>$p->post_name, 'type'=>$p->post_type );
        },
        'permission_callback' => $cap_admin,
        'meta' => array( 'public'=>true, 'mcp'=>array('public'=>true,'type'=>'tool') ),
    ) );

    wp_register_ability( 'wordpress/page-delete', array(
        'label' => 'Delete Page',
        'description' => 'Trash or permanently delete a page/post by ID.',
        'category' => 'wordpress-management',
        'input_schema' => array( 'type'=>'object', 'properties'=>array(
            'id' => array('type'=>'integer'),
            'force' => array('type'=>'boolean'),
        ), 'required'=>array('id') ),
        'output_schema' => array( 'type'=>'boolean' ),
        'execute_callback' => function ( $input ) {
            $res = wp_delete_post( (int)$input['id'], ! empty( $input['force'] ) );
            if ( ! $res ) return new WP_Error( 'delete_failed', 'Delete failed.' );
            return true;
        },
        'permission_callback' => $cap_admin,
        'meta' => array( 'public'=>true, 'mcp'=>array('public'=>true,'type'=>'tool') ),
    ) );

    // --- Posts (generic) ---
    foreach ( array( 'post-get'=>'get', 'post-create'=>'create', 'post-update'=>'update', 'post-delete'=>'delete' ) as $name => $op ) {
        // register get/create/update/delete for posts
    }
    // post get
    wp_register_ability( 'wordpress/post-get', array(
        'label'=>'Get Post','description'=>'Read a post by ID.','category'=>'wordpress-management',
        'input_schema'=>array('type'=>'object','properties'=>array('id'=>array('type'=>'integer')),'required'=>array('id')),
        'output_schema'=>array('type'=>'object'),
        'execute_callback'=>function($input){
            $p=get_post((int)$input['id']); if(!$p) return new WP_Error('not_found','Post not found.');
            return array('id'=>$p->ID,'title'=>$p->post_title,'content'=>$p->post_content,'status'=>$p->post_status,'slug'=>$p->post_name);
        },
        'permission_callback'=>$cap_admin,
        'meta'=>array('public'=>true,'mcp'=>array('public'=>true,'type'=>'tool')),
    ));
    wp_register_ability( 'wordpress/post-create', array(
        'label'=>'Create Post','description'=>'Create a new post.','category'=>'wordpress-management',
        'input_schema'=>array('type'=>'object','properties'=>array(
            'title'=>array('type'=>'string'),'content'=>array('type'=>'string'),'status'=>array('type'=>'string'),'slug'=>array('type'=>'string'),
        ),'required'=>array('title')),
        'output_schema'=>array('type'=>'object'),
        'execute_callback'=>function($input){
            $id=wp_insert_post(array(
                'post_type'=>'post','post_title'=>$input['title'],'post_content'=>$input['content']??'',
                'post_status'=>$input['status']??'draft','post_name'=>$input['slug']??'',
            ),true);
            if(is_wp_error($id)) return $id;
            return array('id'=>$id,'created'=>true);
        },
        'permission_callback'=>$cap_admin,
        'meta'=>array('public'=>true,'mcp'=>array('public'=>true,'type'=>'tool')),
    ));
    wp_register_ability( 'wordpress/post-update', array(
        'label'=>'Update Post','description'=>'Update a post by ID.','category'=>'wordpress-management',
        'input_schema'=>array('type'=>'object','properties'=>array(
            'id'=>array('type'=>'integer'),'title'=>array('type'=>'string'),'content'=>array('type'=>'string'),
            'status'=>array('type'=>'string'),'slug'=>array('type'=>'string'),
        ),'required'=>array('id')),
        'output_schema'=>array('type'=>'object'),
        'execute_callback'=>function($input){
            $id=(int)$input['id']; if(!$id) return new WP_Error('invalid_id','Post ID required.');
            $data=array('ID'=>$id);
            if(isset($input['title'])) $data['post_title']=$input['title'];
            if(isset($input['content'])) $data['post_content']=$input['content'];
            if(isset($input['status'])) $data['post_status']=$input['status'];
            if(isset($input['slug'])) $data['post_name']=$input['slug'];
            $res=wp_update_post($data,true); if(is_wp_error($res)) return $res;
            return array('id'=>$res,'updated'=>true);
        },
        'permission_callback'=>$cap_admin,
        'meta'=>array('public'=>true,'mcp'=>array('public'=>true,'type'=>'tool')),
    ));
    wp_register_ability( 'wordpress/post-delete', array(
        'label'=>'Delete Post','description'=>'Trash or delete a post by ID.','category'=>'wordpress-management',
        'input_schema'=>array('type'=>'object','properties'=>array('id'=>array('type'=>'integer'),'force'=>array('type'=>'boolean')),'required'=>array('id')),
        'output_schema'=>array('type'=>'boolean'),
        'execute_callback'=>function($input){
            $res=wp_delete_post((int)$input['id'], !empty($input['force']));
            if(!$res) return new WP_Error('delete_failed','Delete failed.');
            return true;
        },
        'permission_callback'=>$cap_admin,
        'meta'=>array('public'=>true,'mcp'=>array('public'=>true,'type'=>'tool')),
    ));

    // --- Media upload (base64) ---
    wp_register_ability( 'wordpress/media-upload', array(
        'label'=>'Upload Media','description'=>'Upload a media file from base64 content.','category'=>'wordpress-management',
        'input_schema'=>array('type'=>'object','properties'=>array(
            'filename'=>array('type'=>'string'),
            'mime_type'=>array('type'=>'string'),
            'base64'=>array('type'=>'string'),
        ),'required'=>array('filename','base64')),
        'output_schema'=>array('type'=>'object'),
        'execute_callback'=>function($input){
            $upload=wp_upload_bits( $input['filename'], null, base64_decode( $input['base64'] ) );
            if ( ! empty( $upload['error'] ) ) return new WP_Error( 'upload_failed', $upload['error'] );
            $filetype = wp_check_filetype( $upload['file'] );
            $attachment = array(
                'post_mime_type' => $input['mime_type'] ?? $filetype['type'],
                'post_title'     => sanitize_file_name( $input['filename'] ),
                'post_content'   => '',
                'post_status'    => 'inherit',
            );
            $id = wp_insert_attachment( $attachment, $upload['file'] );
            if ( is_wp_error( $id ) ) return $id;
            require_once ABSPATH . 'wp-admin/includes/image.php';
            $attach_data = wp_generate_attachment_metadata( $id, $upload['file'] );
            wp_update_attachment_metadata( $id, $attach_data );
            return array( 'id'=>$id, 'url'=>$upload['url'] );
        },
        'permission_callback'=>$cap_admin,
        'meta'=>array('public'=>true,'mcp'=>array('public'=>true,'type'=>'tool')),
    ));

    // --- WooCommerce (if active) ---
    if ( class_exists( 'WooCommerce' ) ) {

        wp_register_ability( 'woocommerce/order-create', array(
            'label'=>'Create Order','description'=>'Create a WooCommerce order.','category'=>'wordpress-management',
            'input_schema'=>array('type'=>'object','properties'=>array(
                'customer_id'=>array('type'=>'integer'),
                'status'=>array('type'=>'string'),
                'line_items'=>array('type'=>'array'),
            )),
            'output_schema'=>array('type'=>'object'),
            'execute_callback'=>function($input){
                $order = wc_create_order();
                if ( is_wp_error( $order ) ) return $order;
                if ( ! empty( $input['customer_id'] ) ) $order->set_customer_id( (int)$input['customer_id'] );
                if ( ! empty( $input['status'] ) ) $order->set_status( $input['status'] );
                foreach ( (array) ( $input['line_items'] ?? array() ) as $li ) {
                    $product = wc_get_product( $li['product_id'] ?? 0 );
                    if ( $product ) $order->add_product( $product, $li['quantity'] ?? 1 );
                }
                $order->calculate_totals();
                $order->save();
                return array( 'id'=>$order->get_id(), 'created'=>true );
            },
            'permission_callback'=>$cap_shop,
            'meta'=>array('public'=>true,'mcp'=>array('public'=>true,'type'=>'tool')),
        ));

        wp_register_ability( 'woocommerce/order-refund', array(
            'label'=>'Refund Order','description'=>'Refund a WooCommerce order.','category'=>'wordpress-management',
            'input_schema'=>array('type'=>'object','properties'=>array(
                'order_id'=>array('type'=>'integer'),
                'amount'=>array('type'=>'number'),
                'reason'=>array('type'=>'string'),
            ),'required'=>array('order_id')),
            'output_schema'=>array('type'=>'object'),
            'execute_callback'=>function($input){
                $order = wc_get_order( (int)$input['order_id'] );
                if ( ! $order ) return new WP_Error('not_found','Order not found.');
                $refund = wc_create_refund( array(
                    'order_id'=>$order->get_id(),
                    'amount'=>$input['amount'] ?? $order->get_total(),
                    'reason'=>$input['reason'] ?? '',
                ) );
                if ( is_wp_error( $refund ) ) return $refund;
                return array( 'refund_id'=>$refund->get_id(), 'created'=>true );
            },
            'permission_callback'=>$cap_shop,
            'meta'=>array('public'=>true,'mcp'=>array('public'=>true,'type'=>'tool')),
        ));

        wp_register_ability( 'woocommerce/customer-query', array(
            'label'=>'Query Customers','description'=>'Find WooCommerce customers.','category'=>'wordpress-management',
            'input_schema'=>array('type'=>'object','properties'=>array(
                'id'=>array('type'=>'integer'),
                'email'=>array('type'=>'string'),
                'search'=>array('type'=>'string'),
            )),
            'output_schema'=>array('type'=>'array'),
            'execute_callback'=>function($input){
                if ( ! empty( $input['id'] ) ) {
                    $c = new WC_Customer( (int)$input['id'] );
                    if ( ! $c->get_id() ) return array();
                    return array( array( 'id'=>$c->get_id(), 'email'=>$c->get_email(), 'first_name'=>$c->get_first_name(), 'last_name'=>$c->get_last_name() ) );
                }
                $args = array( 'number'=>50 );
                if ( ! empty( $input['email'] ) ) $args['email'] = $input['email'];
                if ( ! empty( $input['search'] ) ) $args['search'] = $input['search'];
                $users = get_users( $args );
                $out = array();
                foreach ( $users as $u ) {
                    $c = new WC_Customer( $u->ID );
                    $out[] = array( 'id'=>$c->get_id(), 'email'=>$c->get_email(), 'first_name'=>$c->get_first_name(), 'last_name'=>$c->get_last_name() );
                }
                return $out;
            },
            'permission_callback'=>$cap_shop,
            'meta'=>array('public'=>true,'mcp'=>array('public'=>true,'type'=>'tool')),
        ));

        wp_register_ability( 'woocommerce/customer-update', array(
            'label'=>'Update Customer','description'=>'Update a WooCommerce customer.','category'=>'wordpress-management',
            'input_schema'=>array('type'=>'object','properties'=>array(
                'id'=>array('type'=>'integer'),
                'first_name'=>array('type'=>'string'),
                'last_name'=>array('type'=>'string'),
                'email'=>array('type'=>'string'),
            ),'required'=>array('id')),
            'output_schema'=>array('type'=>'object'),
            'execute_callback'=>function($input){
                $c = new WC_Customer( (int)$input['id'] );
                if ( ! $c->get_id() ) return new WP_Error('not_found','Customer not found.');
                if ( isset( $input['first_name'] ) ) $c->set_first_name( $input['first_name'] );
                if ( isset( $input['last_name'] ) ) $c->set_last_name( $input['last_name'] );
                if ( isset( $input['email'] ) ) $c->set_email( $input['email'] );
                $c->save();
                return array( 'id'=>$c->get_id(), 'updated'=>true );
            },
            'permission_callback'=>$cap_shop,
            'meta'=>array('public'=>true,'mcp'=>array('public'=>true,'type'=>'tool')),
        ));

        wp_register_ability( 'woocommerce/coupon-create', array(
            'label'=>'Create Coupon','description'=>'Create a WooCommerce coupon.','category'=>'wordpress-management',
            'input_schema'=>array('type'=>'object','properties'=>array(
                'code'=>array('type'=>'string'),
                'discount_type'=>array('type'=>'string'),
                'amount'=>array('type'=>'number'),
            ),'required'=>array('code')),
            'output_schema'=>array('type'=>'object'),
            'execute_callback'=>function($input){
                $coupon = new WC_Coupon();
                $coupon->set_code( $input['code'] );
                if ( isset( $input['discount_type'] ) ) $coupon->set_discount_type( $input['discount_type'] );
                if ( isset( $input['amount'] ) ) $coupon->set_amount( $input['amount'] );
                $id = $coupon->save();
                return array( 'id'=>$id, 'created'=>true );
            },
            'permission_callback'=>$cap_shop,
            'meta'=>array('public'=>true,'mcp'=>array('public'=>true,'type'=>'tool')),
        ));

        wp_register_ability( 'woocommerce/coupon-update', array(
            'label'=>'Update Coupon','description'=>'Update a WooCommerce coupon by ID.','category'=>'wordpress-management',
            'input_schema'=>array('type'=>'object','properties'=>array(
                'id'=>array('type'=>'integer'),
                'code'=>array('type'=>'string'),
                'discount_type'=>array('type'=>'string'),
                'amount'=>array('type'=>'number'),
            ),'required'=>array('id')),
            'output_schema'=>array('type'=>'object'),
            'execute_callback'=>function($input){
                $coupon = new WC_Coupon( (int)$input['id'] );
                if ( ! $coupon->get_id() ) return new WP_Error('not_found','Coupon not found.');
                if ( isset( $input['code'] ) ) $coupon->set_code( $input['code'] );
                if ( isset( $input['discount_type'] ) ) $coupon->set_discount_type( $input['discount_type'] );
                if ( isset( $input['amount'] ) ) $coupon->set_amount( $input['amount'] );
                $coupon->save();
                return array( 'id'=>$coupon->get_id(), 'updated'=>true );
            },
            'permission_callback'=>$cap_shop,
            'meta'=>array('public'=>true,'mcp'=>array('public'=>true,'type'=>'tool')),
        ));

        wp_register_ability( 'woocommerce/product-category-create', array(
            'label'=>'Create Product Category','description'=>'Create a WooCommerce product category.','category'=>'wordpress-management',
            'input_schema'=>array('type'=>'object','properties'=>array(
                'name'=>array('type'=>'string'),
                'slug'=>array('type'=>'string'),
            ),'required'=>array('name')),
            'output_schema'=>array('type'=>'object'),
            'execute_callback'=>function($input){
                $res = wp_insert_term( $input['name'], 'product_cat', array( 'slug'=>$input['slug']??'' ) );
                if ( is_wp_error( $res ) ) return $res;
                return array( 'id'=>$res['term_id'], 'created'=>true );
            },
            'permission_callback'=>$cap_shop,
            'meta'=>array('public'=>true,'mcp'=>array('public'=>true,'type'=>'tool')),
        ));

        wp_register_ability( 'woocommerce/product-category-update', array(
            'label'=>'Update Product Category','description'=>'Update a WooCommerce product category.','category'=>'wordpress-management',
            'input_schema'=>array('type'=>'object','properties'=>array(
                'id'=>array('type'=>'integer'),
                'name'=>array('type'=>'string'),
                'slug'=>array('type'=>'string'),
            ),'required'=>array('id')),
            'output_schema'=>array('type'=>'object'),
            'execute_callback'=>function($input){
                $data=array();
                if(isset($input['name'])) $data['name']=$input['name'];
                if(isset($input['slug'])) $data['slug']=$input['slug'];
                $res = wp_update_term( (int)$input['id'], 'product_cat', $data );
                if ( is_wp_error( $res ) ) return $res;
                return array( 'id'=>(int)$input['id'], 'updated'=>true );
            },
            'permission_callback'=>$cap_shop,
            'meta'=>array('public'=>true,'mcp'=>array('public'=>true,'type'=>'tool')),
        ));

        wp_register_ability( 'woocommerce/inventory-update', array(
            'label'=>'Update Inventory','description'=>'Update product stock quantity.','category'=>'wordpress-management',
            'input_schema'=>array('type'=>'object','properties'=>array(
                'product_id'=>array('type'=>'integer'),
                'quantity'=>array('type'=>'integer'),
            ),'required'=>array('product_id','quantity')),
            'output_schema'=>array('type'=>'object'),
            'execute_callback'=>function($input){
                $product = wc_get_product( (int)$input['product_id'] );
                if ( ! $product ) return new WP_Error('not_found','Product not found.');
                $product->set_stock_quantity( (int)$input['quantity'] );
                $product->set_manage_stock( true );
                $product->save();
                return array( 'product_id'=>$product->get_id(), 'quantity'=>$product->get_stock_quantity() );
            },
            'permission_callback'=>$cap_shop,
            'meta'=>array('public'=>true,'mcp'=>array('public'=>true,'type'=>'tool')),
        ));
    }

} );

// Menu abilities
add_action( 'wp_abilities_api_init', function () {
    $cap_admin = function () { return current_user_can( 'manage_options' ); };

    wp_register_ability( 'wordpress/menu-list', array(
        'label'=>'List Menus','description'=>'List navigation menus.','category'=>'wordpress-management',
        'input_schema'=>array('type'=>'object','properties'=>array()),
        'output_schema'=>array('type'=>'array'),
        'execute_callback'=>function($input){
            $menus = wp_get_nav_menus();
            $out = array();
            foreach ( $menus as $m ) $out[] = array( 'id'=>$m->term_id, 'name'=>$m->name, 'slug'=>$m->slug, 'count'=>$m->count );
            return $out;
        },
        'permission_callback'=>$cap_admin,
        'meta'=>array('public'=>true,'mcp'=>array('public'=>true,'type'=>'tool')),
    ));

    wp_register_ability( 'wordpress/menu-update', array(
        'label'=>'Update Menu','description'=>'Update a navigation menu name/slug.','category'=>'wordpress-management',
        'input_schema'=>array('type'=>'object','properties'=>array(
            'id'=>array('type'=>'integer'),
            'name'=>array('type'=>'string'),
            'slug'=>array('type'=>'string'),
        ),'required'=>array('id')),
        'output_schema'=>array('type'=>'object'),
        'execute_callback'=>function($input){
            $id=(int)$input['id']; if(!$id) return new WP_Error('invalid_id','Menu ID required.');
            $data=array(); if(isset($input['name'])) $data['name']=$input['name']; if(isset($input['slug'])) $data['slug']=$input['slug'];
            $res=wp_update_term($id,'nav_menu',$data);
            if(is_wp_error($res)) return $res;
            return array('id'=>$id,'updated'=>true);
        },
        'permission_callback'=>$cap_admin,
        'meta'=>array('public'=>true,'mcp'=>array('public'=>true,'type'=>'tool')),
    ));
} );
