<?php
/**
 * Part of the Platform application.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Platform
 * @version    1.0.3
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @link       http://cartalyst.com
 */


/*
 * --------------------------------------------------------------------------
 * What we can use in this class.
 * --------------------------------------------------------------------------
 */
use Platform\Settings\Model\Setting;


/**
 * --------------------------------------------------------------------------
 * Settings > API Class
 * --------------------------------------------------------------------------
 *
 * API class to manage the settings.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.1
 */
class Settings_API_Settings_Controller extends API_Controller
{
    /**
     * --------------------------------------------------------------------------
     * Function: get_index()
     * --------------------------------------------------------------------------
     *
     * Gets a group of settings by the given parameters.
     *
     *  <code>
     *      API::get('settings', $conditions);
     *  </code>
     *
     * @access   public
     * @return   Response
     */
    public function get_index()
    {
        // Get the inputs.
        //
        $where    = Input::get('where');
        $organize = Input::get('organize', false);

        // Get the settings based on the request.
        //
        $result = Setting::all(function($query) use ($where)
        {
            if ( ! is_array($where) or ! is_array($where[0]))
            {
                $where = array($where);
            }

            foreach ($where as $w)
            {
                if (count($w) == 3)
                {
                    $query = $query->where($w[0], $w[1], $w[2]);
                }
            }

            return $query;
        });

        // If there is no result.
        //
        if ( ! $result)
        {
            return new Response(array(
                'message' => Lang::line('settings::messages.errors.none_found')->get()
            ), API::STATUS_NOT_FOUND);
        }

        // Do we want to return an organized array ?
        //
        if ($organize)
        {
            // Initialize an empty array.
            //
            $settings = array();

            // Sort the settings array.
            //
            foreach ($result as $setting)
            {
                $settings[ $setting['type'] ][ $setting['name'] ] = $setting;
            }

            // Save the settings in our result variable.
            //
            $result = $settings;
            unset($settings);
        }

        // Return the result.
        //
        return new Response($result);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: put_index()
     * --------------------------------------------------------------------------
     *
     * Update the settings in the database.
     *
     *  <code>
     *      API::put('settings', array('settings' => $settings));
     *  </code>
     *
     * @access   public
     * @return   Response
     */
    public function put_index()
    {
        // Initialize some arrays.
        //
        $updated = array();
        $errors  = array();

        // Get the data.
        //
        $settings = Input::get('settings');

        // Loop through the settings.
        //
        foreach ($settings as $setting)
        {
            // Validation rules.
            //
            $validation = $setting['validation'];
            unset($setting['validation']);

            // Lets make sure the values are set.
            //
            $setting['extension'] = array_get($setting, 'extension') ?: '';
            $setting['type']      = array_get($setting, 'type')      ?: '';
            $setting['name']      = array_get($setting, 'name')      ?: '';
            $setting['value']     = array_get($setting, 'value')     ?: '';

            // Type is option, so we'll set it to the name of the extension.
            //
            $setting['type'] = array_get($setting, 'type') ?: $setting['extension'];

            // Very optional
            //
            $setting['id'] = array_get($setting, 'id') ?: null;

            //
            //
            $setting_model = Setting::find(function($query) use($setting)
            {
                // If the ID was passed, we'll just use that to find the setting.
                //
                if ( ! is_null( $setting['id'] ) )
                {
                    return $query->where('id', '=', $setting['id']);
                }

                return $query
                    ->where('extension', '=', $setting['extension'])
                    ->where('type', '=', $setting['type'])
                    ->where('name', '=', $setting['name']);
            });

            // If setting model doesn't exist, make one
            //
            if ( ! $setting_model)
            {
                unset( $setting['id'] );
                $setting_model = new Setting($setting);
            }

            // Otherwise update the values.
            //
            else
            {
                $setting_model->extension = $setting['extension'];
                $setting_model->type      = $setting['type'];
                $setting_model->name      = $setting['name'];
                $setting_model->value     = $setting['value'];
            }

            // Fallback and a rules reseter.
            //
            $rules = array();

            // Set this setting rules.
            //
            if ($validation)
            {
                $rules = $validation;
            }

            // Pass the validation rules to the model.
            //
            $setting_model->set_validation($validation);

            try
            {
                // Save the setting.
                //
                if ($setting_model->save())
                {
                    $updated[] = ucfirst($setting_model->name);
                }

                // Setting failed the validation
                //
                else
                {
                    // Get the errors.
                    //
                    foreach ($setting_model->validation()->errors->all() as $error)
                    {
                        $errors[] = $error;
                    }
                }
            }
            catch (Exception $e)
            {
                $errors[] = $e->getMessage();
            }
        }

        // Return the updated and non updated settings.
        //
        return new Response(array('updated' => $updated, 'errors' => $errors));
    }
}
