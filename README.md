# Cake Admin Plugin

This is a plugin for cakephp 2.x to bake nicer looking / working admin sites.

<img src="http://labs.aboutme.be/cakephp-admin/screenshot-detail.jpg">

Some of the features are:

*	Simple authorization
*	Showing related data in tabs
*	Ajax paginated overviews
*	Easy-to-add search options to model overviews
*	Integrated ckfinder support for file uploads
*	Integrated ckeditor for textareas

## Installation

1. Put the contents of the Plugin folder into the `app/Plugin` folder of your cakephp project.
2. Put the contents of the webroot folder into the `app/webroot` folder of your cakephp project.
3. Load the plugin by adding this line at the bottom of your `app/Config/bootstrap.php`:
    
    	CakePlugin::load('Admin', array('bootstrap' => true, 'routes' => true));

4. Add the toString behaviour to your `app/Model/AppModel.php`

		class AppModel extends Model {
			public $actsAs = array('Admin.ToString');

5. Go to http://localhost/yourproject/admin/users/add and add the first admin user
6. Remove the auth allow lines from the AdminAppController beforeFilter() method to activate the authentication:

		public function beforeFilter() {
        	//TODO: remove after you created the first user
        	$this->Auth->allow();
        	return;
            //end remove


## Running the bake shell

Most of the time, you will run the bake shell, to generate the latest controllers & views, based on your models. So, each time you make changes in your database layout or model classes, make sure to run the shell:

	cd cakephp-installation/app
	Console/cake Admin.backsite
	
This will automatically create / update your files in the Admin plugin directory.

## Customization

### Hiding / Mapping field types

There are a couple of options, regarding fields to hide, mapping of input types to model properties, … you can set in `app/Plugin/Admin/Config/console.php`

### Menu bar

Another thing you might want to update is the menu. This is done in the AppController of the plugin `app/Plugin/Admin/Controller/AdminAppController.php` by making changes to the `$menuItems` variable in the `beforeRender()` callback.

### Additional controller methods

You can also bake additional, custom methods into the admin controllers.

1. Create a directory with the name of your controller (eg: Users) in the `app/Plugin/Admin/Console/Templates/backsite/actions` folder.
2. Inside that directory, you create a file named `additional_functions.ctp` with your extra functions. 

This is done for the Users controller by default, to add the login() and logout() functions - so you can base your templates on that.

### Custom view templates

You can choose not to use the default view template for a certain controller action, but provide one of your own. 

1. Create a directory with the name of your controller (eg: Users) in the `app/Plugin/Admin/Console/Templates/backsite/views` folder.
2. Inside that directory, you create a file with the name of the action you want a custom view for.

This is done for the login action of the Users controller by default.