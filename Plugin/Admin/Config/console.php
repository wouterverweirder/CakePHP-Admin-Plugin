<?php

/**
 * Specify which actions are disabled for certain models
 * examples:
 * 		'ReadOnlyModelName' => array('add', 'edit', 'delete'),
 *		'InsertAndViewOnlyModelName' => array('edit', 'delete')
 */
$config['admin.console.models.disabledActions'] = array(
);

/**
 * Define searchable fields for the admin overview pages
 */
$config['admin.console.views.index.searchable_fields'] = array(
	//example of defining search field for the index pages:
	//'Product' => array(
	//	'name' => 'Product'
	//)
);

/**
 * Mapping certain field names to predefined form fields
 */
$config['admin.console.views.form.field_types'] = array(
	'all' => array(
		'parent_id' => 'parent_id',
		'psword' => 'password',
		'passwd' => 'password',
		'password' => 'password',
        'image' => 'image',
        'image1' => 'image',
        'image2' => 'image',
        'afbeelding' => 'image',
        'foto' => 'image',
        'pdf' => 'file'
	)
);

/**
 * Fields we won't show in the form pages
 */
$config['admin.console.views.form.hidden_fields'] = array(
	'all' => array(
	    'created',
	    'modified',
	    'updated',
	    'lft',
	    'rght')
);

/**
 * Fields we won't show in the overview tables
 */
$config['admin.console.views.table.hidden_fields'] = array(
	'all' => array(
	    'id',
	    'password',
	    'passwd',
	    'deleted_date',
	    'deleted',
	    'created',
	    'lft',
	    'rght')
);

/**
 * Override visible fields for overview tables and show these ones
 * Use the model name as key, value is an array of fieldnames to show
 */
$config['admin.console.views.table.explicit_fields'] = array(
);

/**
 * Fields we won't show in the detail pages
 */
$config['admin.console.views.view.hidden_fields'] = array(
	'all' => array(
    )
);

/**
 * Tabs we won't show in the detail pages
 * Use Model name as key, and relationship alias as values
 */
$config['admin.console.views.view.hidden_tabs'] = array(
	'all' => array(
    ),
);