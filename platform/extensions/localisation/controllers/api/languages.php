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
 * @version    1.0.1
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
use Localisation\Language;


/**
 * --------------------------------------------------------------------------
 * Localisation > Languages > API Class
 * --------------------------------------------------------------------------
 * 
 * Manage the languages.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.0
 */
class Localisation_API_Languages_Controller extends API_Controller
{
    /**
     * --------------------------------------------------------------------------
     * Function: get_index()
     * --------------------------------------------------------------------------
     *
     * Returns an array of all the languages.
     *
     * If you want to retrieve information about a specific language, you can
     * pass the language code, the language id or the language slug as the 
     * last parameter.
     *
     *  <code>
     *      $all_languages = API::get('localisation/languages');
     *      $en_language   = API::get('localisation/language/1');
     *      $en_language   = API::get('localisation/language/en');
     *      $en_language   = API::get('localisation/language/english');
     *  </code>
     *
     * @access   public
     * @param    mixed
     * @return   Response
     */
    public function get_index($language_code)
    {
        // If we have the language code, we return the information about that language.
        //
        if ($language_code != false)
        {
            // Get this language information.
            //
            $language = Language::find($language_code);

            // Check if the language exists.
            //
            if (is_null($language))
            {
                // Language not found.
                //
                return new Response(array(
                    'message' => Lang::line('localisation::languages/message.error.not_found', array('language' => $language_code))->get()
                ), API::STATUS_NOT_FOUND);
            }

            // Return the language information.
            //
            return new Response($language);
        }

        // Get and return all the languages.
        //
        return new Response(Language::all());
    }


    /**
     * --------------------------------------------------------------------------
     * Function: post_index()
     * --------------------------------------------------------------------------
     *
     * Creates a new language.
     *
     *  <code>
     *      API::post('localisation/language');
     *  </code>
     *
     * @access   public
     * @return   Response
     */
    public function post_index()
    {
        // Create the language.
        //
        $language = new Language();

        // Update the language data.
        //
        $language->name   = Input::get('name');
        $language->slug   = \Str::slug(Input::get('name'));
        $language->code   = strtoupper(Input::get('code'));
        $language->locale = Input::get('locale');
        $language->status = Input::get('status');

        try
        {
            // Save the language.
            //
            if ($language->save())
            {
                // Return a response.
                //
                return new Response(array(
                    'message' => Lang::line('localisation::languages/message.create.success', array('language' => $language->name))->get(),
                    'slug'    => $language->slug
                ), API::STATUS_CREATED);
            }

            // An error occurred.
            //
            else
            {
                // Return a response.
                //
                return new Response(array(
                    'message' => Lang::line('localisation::languages/message.create.fail')->get(),
                    'errors'  => ($language->validation()->errors->has()) ? $language->validation()->errors->all() : array()
                ), ($language->validation()->errors->has()) ? API::STATUS_BAD_REQUEST : API::STATUS_UNPROCESSABLE_ENTITY);
            }
        }
        catch (Exception $e)
        {
            // Return a response.
            //
            return new Response(array(
                'message' => $e->getMessage()
            ), API::STATUS_BAD_REQUEST);
        }
    }


    /**
     * --------------------------------------------------------------------------
     * Function: put_index()
     * --------------------------------------------------------------------------
     *
     * Edits a given language using the provided language id, language code 
     * or by using the language slug.
     *
     *  <code>
     *      API::put('localisation/language/1');
     *      API::put('localisation/language/en');
     *      API::put('localisation/language/english');
     *  </code>
     *
     * @access   public
     * @param    mixed
     * @return   Response
     */
    public function put_index($language_code)
    {
        // Get this language information.
        //
        $language = Language::find($language_code);

        // Now update the rules.
        //
        Language::set_validation(array(
            'code' => 'required|size:2|unique:languages,code,' . $language->code . ',code'
        ));

        // Update the language data.
        //
        $language->name   = Input::get('name');
        $language->slug   = \Str::slug(Input::get('name'));
        $language->code   = strtoupper(Input::get('code'));
        $language->locale = Input::get('locale');
        $language->status = ( ! $language['default'] ? Input::get('status') : 1 );

        try
        {
            // Update the language.
            //
            if ($language->save())
            {
                // Return a response.
                //
                return new Response(array(
                    'slug'    => $language->slug,
                    'message' => Lang::line('localisation::languages/message.update.success', array('language' => $language['name']))->get()
                ));
            }
            else
            {
                // Return a response.
                //
                return new Response(array(
                    'message' => Lang::line('localisation::languages/message.update.fail', array('language' => $language['name']))->get(),
                    'errors'  => ($language->validation()->errors->has()) ? $language->validation()->errors->all() : array()
                ), ($language->validation()->errors->has()) ? API::STATUS_BAD_REQUEST : API::STATUS_UNPROCESSABLE_ENTITY);
            }
        }
        catch (Exception $e)
        {
            // Return a response.
            //
            return new Response(array(
                'message' => $e->getMessage()
            ), API::STATUS_BAD_REQUEST);
        }
    }



    /**
     * --------------------------------------------------------------------------
     * Function: delete_index()
     * --------------------------------------------------------------------------
     *
     * Deletes a given language using the provided language id, language code 
     * or by using the language slug.
     *
     *  <code>
     *      $language = API::delete('localisation/language/1');
     *      $language = API::delete('localisation/language/en');
     *      $language = API::delete('localisation/language/english');
     *  </code>
     *
     * @access   public
     * @param    mixed
     * @return   Response
     */
    public function delete_index($language_code)
    {
        try
        {
            // Get this language information.
            //
            $language = Language::find($language_code);
        }
        catch (Exception $e)
        {
            // Return a response.
            //
            return new Response(array(
                'message' => Lang::line('localisation::languages/message.error.not_found', array('language' => $language_code))->get()
            ), API::STATUS_NOT_FOUND);
        }

        // Check if this is a default language.
        //
        if ($language['default'])
        {
            // Return a response.
            //
            return new Response( array(
                'message' => Lang::line('localisation::languages/message.delete.single.being_used')->get()
            ), API::STATUS_BAD_REQUEST);
        }

        // Try to delete the language.
        //
        try
        {
            // Delete the language.
            //
            $language->delete();

            // Return a response.
            //
            return new Response(array(
                'message' => Lang::line('localisation::languages/message.delete.single.success', array('language' => $language->name))->get()
            ));
        }
        catch (Exception $e)
        {
            // Return a response.
            //
            return new Response( array(
                'message' => Lang::line('localisation::languages/message.delete.single.fail', array('language' => $language->name))->get()
            ), API::STATUS_BAD_REQUEST);
        }
    }


    /**
     * --------------------------------------------------------------------------
     * Function: get_datatable()
     * --------------------------------------------------------------------------
     *
     * Returns fields required for a Platform.table.
     *
     *  <code>
     *      API::get('localisation/languages/datatable');
     *  </code>
     *
     * @access   public
     * @param    string
     * @return   Response
     */
    public function get_datatable()
    {
        // Get the default language.
        //
        $default_language = strtoupper(Platform::get('localisation.site.language'));


        $defaults = array(
            'select'   => array(
                'languages.id'         => 'ID',
                'languages.name'       => 'Name',
                'languages.code'       => 'Code',
                'languages.slug'       => 'slug'
            ),
            'where'    => array(),
            'order_by' => array('languages.name' => 'asc')
        );

        // Count the total of languages.
        //
        $count_total = Language::count();

        // get the filtered count
        // we set to distinct because a user can be in multiple groups
        $count_filtered = Language::count_distinct('languages.id', function($query) use ($defaults)
        {
            // sets the where clause from passed settings
            return Table::count($query, $defaults);
        });

        // Set the pagination.
        //
        $paging = Table::prep_paging($count_filtered, 20);

        // Get the languages.
        //
        $items = Language::all(function($query) use ($defaults, $paging)
        {
            list($query, $columns) = Table::query($query, $defaults, $paging);

            return $query->select($columns);
        });

        // Return our data.
        //
        return new Response(array(
            'rows'             => ( $items ?: array() ),
            'count'            => $count_total,
            'count_filtered'   => $count_filtered,
            'paging'           => $paging,
            'default_language' => $default_language
        ));
    }


    /**
     * --------------------------------------------------------------------------
     * Function: put_default()
     * --------------------------------------------------------------------------
     *
     * Makes a language the default language on the system.
     *
     *  <code>
     *      API::put('localisation/language/default/1');
     *      API::put('localisation/language/default/en');
     *      API::put('localisation/language/default/english');
     *  </code>
     *
     * @access   public
     * @param    mixed
     * @return   Response
     */
    public function put_default($language_code)
    {
        // Get this language information.
        //
        $language = Language::find($language_code);
        
        // Check if the language exists.
        //
        if (is_null($language))
        {
            // Return a response.
            //
            return new Response(array(
                'message' => Lang::line('localisation::languages/message.error.not_found', array('language' => $language_code))->get()
            ), API::STATUS_NOT_FOUND);
        }

        // Update the settings table.
        //
        DB::table('settings')
            ->where('extension', '=', 'localisation')
            ->where('type', '=', 'site')
            ->where('name', '=', 'language')
            ->update(array('value' => $language['code']));

        // Return a response.
        //
        return new Response(array(
            'message' => Lang::line('localisation::languages/message.update.default', array('language' => $language->name))->get()
        ));
    }
}
