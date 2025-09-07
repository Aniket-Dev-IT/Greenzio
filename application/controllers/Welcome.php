<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		// Load models
		$this->load->model('products');
		
		// Get featured products or latest products for homepage
		try {
			$data['featured_products'] = $this->products->getFeaturedProducts(8);
		} catch (Exception $e) {
			// Fallback to get any products
			$data['featured_products'] = $this->products->getAllProducts(8);
		}
		
		// Load views
		$this->load->view('main/header');
		$this->load->view('main/homepage', $data);
		$this->load->view('main/footer');
	}
}
