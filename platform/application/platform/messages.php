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
 * @copyright  (c) 2011-2012, Cartalyst LLC
 * @link       http://cartalyst.com
 */


/*
 * --------------------------------------------------------------------------
 * What we can use in this class.
 * --------------------------------------------------------------------------
 */
use Laravel\IoC;


/**
 * --------------------------------------------------------------------------
 * Extensions Manager Class
 * --------------------------------------------------------------------------
 *
 * Messages container - used for storing feedback for system operations
 * within Platform.
 *
 * @package    Platform
 * @author     Cartalyst LLC
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @license    BSD License (3-clause)
 * @link       http://cartalyst.com
 * @version    1.1
 */
class Messages extends Laravel\Messages
{
    /**
     * The key used in the IoC instance.
     *
     * @access   protected
     * @var      string
     */
    protected static $ioc_key = 'messages';

    /**
     * The key used for the messages property in the Session.
     *
     * @access   protected
     * @var string
     */
    protected $session_key = 'platform';


    /**
     * --------------------------------------------------------------------------
     * Function: __construct()
     * --------------------------------------------------------------------------
     *
     * Create a new Messages instance
     *
     * @access   public
     * @param    array
     * @return   void
     */
    public function __construct($messages = array())
    {
        // After the controller has been executed we want 
        // to persist the messages instance to the session.
        //
        Event::listen('platform.controller.after', function()
        {
            Messages::instance()->persist();
        });

        // Call parent.
        //
        parent::__construct();
    }


    /**
     * --------------------------------------------------------------------------
     * Function: persist()
     * --------------------------------------------------------------------------
     *
     * Persists the Messages to the session.
     *
     * @access   public
     * @return   void
     */
    public function persist()
    {
        Session::flash($this->session_key, $this->messages);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: error()
     * --------------------------------------------------------------------------
     *
     * Adds an error message.
     *
     * @access   public
     * @param    string
     * @return   object
     */
    public function error($message)
    {
        return $this->add('errors', $message);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: warning()
     * --------------------------------------------------------------------------
     *
     * Adds a warning message.
     *
     * @access   public
     * @param    string
     * @return   object
     */
    public function warning($message)
    {
        return $this->add('warnings', $message);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: success()
     * --------------------------------------------------------------------------
     *
     * Adds a success message.
     *
     * @access   public
     * @param    string
     * @return   object
     */
    public function success($message)
    {
        return $this->add('success', $message);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: info()
     * --------------------------------------------------------------------------
     *
     * Adds an info message.
     *
     * @access   public
     * @param    string
     * @return   object
     */
    public function info($message)
    {
        return $this->add('info', $message);
    }


    /**
     * --------------------------------------------------------------------------
     * Function: add()
     * --------------------------------------------------------------------------
     *
     * Stores the messages in the array.
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   void
     */
    public function add($key, $message)
    {
        // The message needs to be in an array. 
        //
        $message = ( ! is_array($message)) ? array($message) : $message;

        // Check if the type exists in the messages array.
        //
        if (isset($this->messages[ $key ]))
        {
            $this->messages[ $key ] = array_merge($this->messages[ $key ], $message);
        }

        // It is a new type message.
        //
        else
        {
            $this->messages[ $key ] = $message;
        }
    }


    /**
     * --------------------------------------------------------------------------
     * Function: instance()
     * --------------------------------------------------------------------------
     *
     * Returns one instance of the messages object.
     *
     * Note, all parameters will be passed to a new instance, they won't be 
     * used to access future instances.
     *
     * @access   public
     * @param    array
     * @param    boolean
     * @return   object
     */
    public static function instance($messages = array(), $use_session = false)
    {
        // Register a singleton with IoC container.
        //
        IoC::singleton(static::$ioc_key, function($messages = array(), $use_session = false)
        {
            return new Messages($messages, $use_session);
        });

        return IoC::resolve(static::$ioc_key, array($messages, $use_session));
    }
}
