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
