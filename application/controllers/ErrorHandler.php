<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ErrorHandler Controller
 * 
 * Handles HTTP errors and displays appropriate error pages
 * (Renamed from Error to avoid conflict with PHP 7+ built-in Error class)
 * 
 * @package     Greenzio
 * @subpackage  Controllers
 * @author      Greenzio Team
 */
class ErrorHandler extends CI_Controller {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 404 Page Not Found
     */
    public function error_404()
    {
        $this->output->set_status_header('404');
        $data['page_title'] = '404 - Page Not Found';
        $data['heading'] = 'Oops! Page Not Found';
        $data['message'] = 'We couldn\'t find the page you\'re looking for.';

        $this->load->view('main/header');
        $this->load->view('errors/custom_error', $data);
        $this->load->view('main/footer');
    }

    /**
     * 401 Unauthorized
     */
    public function error_401()
    {
        $this->output->set_status_header('401');
        $data['page_title'] = '401 - Unauthorized';
        $data['heading'] = 'Authentication Required';
        $data['message'] = 'You need to be logged in to access this page.';

        $this->load->view('main/header');
        $this->load->view('errors/custom_error', $data);
        $this->load->view('main/footer');
    }

    /**
     * 403 Forbidden
     */
    public function error_403()
    {
        $this->output->set_status_header('403');
        $data['page_title'] = '403 - Forbidden';
        $data['heading'] = 'Access Denied';
        $data['message'] = 'You don\'t have permission to access this resource.';

        $this->load->view('main/header');
        $this->load->view('errors/custom_error', $data);
        $this->load->view('main/footer');
    }

    /**
     * 500 Internal Server Error
     */
    public function error_500()
    {
        $this->output->set_status_header('500');
        $data['page_title'] = '500 - Server Error';
        $data['heading'] = 'Internal Server Error';
        $data['message'] = 'Something went wrong on our end. We\'re working to fix it!';

        $this->load->view('main/header');
        $this->load->view('errors/custom_error', $data);
        $this->load->view('main/footer');
    }

    /**
     * Database Error
     */
    public function db_error()
    {
        $this->output->set_status_header('500');
        $data['page_title'] = 'Database Error';
        $data['heading'] = 'Database Error';
        $data['message'] = 'There was a problem connecting to the database. Please try again later.';

        $this->load->view('main/header');
        $this->load->view('errors/custom_error', $data);
        $this->load->view('main/footer');
    }

    /**
     * Maintenance Mode
     */
    public function maintenance()
    {
        $this->output->set_status_header('503');
        $data['page_title'] = 'Site Maintenance';
        $data['heading'] = 'We\'ll Be Back Soon!';
        $data['message'] = 'Greenzio is currently undergoing scheduled maintenance. Please check back later.';

        $this->load->view('errors/maintenance', $data);
    }

    /**
     * General Error Page
     */
    public function index()
    {
        $this->error_404();
    }

}
