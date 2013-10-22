# Cake Admin Plugin

This is a plugin for cakephp 2.x to bake nicer looking / working admin sites.

Some of the features are:

*	Twitter bootstrap components
*	Simple authorization
*	Showing related data in tabs
*	Ajax paginated overviews
*	Easy-to-add search options to model overviews
*	Integrated ckfinder support for file uploads
*	Integrated ckeditor for textareas

## Installation

1. Put the contents of the Plugin folder into the `app/Plugin` folder of your cakephp project.
2. Put the contents of the Model folder into the `app/Model` folder of your cakephp project.
3. Put the contents of the webroot folder into the `app/webroot` folder of your cakephp project.
4. Load the plugin by adding this line at the bottom of your `app/Config/bootstrap.php`:
    
    	CakePlugin::load('Admin', array('bootstrap' => true, 'routes' => true));

5. Add the toString behaviour and the equaltofield validation rule to your `app/Model/AppModel.php`

		class AppModel extends Model {
			public $actsAs = array('Admin.ToString');
			/**
		     * validation rule to compare two fields
		     * used for password validation in create/update forms
		     * @param $check
		     * @param $otherfield
		     * @return bool
		     */
		    public function equaltofield($check, $otherfield) {
		        //get name of field
		        $fname = '';
		        foreach ($check as $key => $value){
		            $fname = $key;
		            break;
		        }
		        return $this->data[$this->name][$otherfield] === $this->data[$this->name][$fname];
		    }

6. Go to http://localhost/yourproject/admin/users/add and add the first admin user
7. Remove the auth allow lines from the AdminAppController beforeFilter() method to activate the authentication:

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

Take a look at `app/Plugin/Admin/Config/console.php` for some general settings.

### Hiding / Mapping field types

There are a couple of options, regarding fields to hide, mapping of input types to model properties, … you can set in `app/Plugin/Admin/Config/console.php`

### Custom controller methods

You can use your own, custom methods in admin controllers:

1. Create a directory with the name of your controller (eg: Users) in the `app/Plugin/Admin/Console/Templates/backsite/actions` folder.
2. Inside that directory, you create a file with the name of your action (eg: view.ctp). 

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