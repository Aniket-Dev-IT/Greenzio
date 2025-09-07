<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		// Load products model for homepage
		$this->load->model('products');
		
		// Get featured products from existing categories for homepage
		$data['featured_fruits'] = $this->products->productsByCategory('Fruits & Vegetables', null, 4);
		$data['featured_dairy'] = $this->products->productsByCategory('Dairy & Bakery', null, 4);
		$data['featured_grains'] = $this->products->productsByCategory('Grains & Rice', null, 4);
		$data['featured_spices'] = $this->products->productsByCategory('Spices & Seasonings', null, 4);
		$data['featured_snacks'] = $this->products->productsByCategory('Snacks & Instant Food', null, 4);
		$data['featured_personal'] = $this->products->productsByCategory('Personal Care', null, 4);
		
		$this->load->view('main/header');
		$this->load->view('main/homepage', $data);
		$this->load->view('main/footer');
	}

	public function contact()
	{
		$this->load->view('main/header');
		$this->load->view('main/contact');
		$this->load->view('main/footer');
	}

	// Grocery category methods using existing database categories
	public function fruits_vegetables()
	{
		$this->load->model('products');
		$data['products'] = $this->products->productsByCategory('Fruits & Vegetables');
		$data['category'] = 'Fruits & Vegetables';
		$data['subcategories'] = $this->products->getSubcategories('Fruits & Vegetables');
		
		$this->load->view('main/header');
		$this->load->view('pages/category_products', $data);
		$this->load->view('main/footer');
	}

	public function dairy_products()
	{
		$this->load->model('products');
		$data['products'] = $this->products->productsByCategory('Dairy & Bakery');
		$data['category'] = 'Dairy & Bakery';
		$data['subcategories'] = $this->products->getSubcategories('Dairy & Bakery');
		
		$this->load->view('main/header');
		$this->load->view('pages/category_products', $data);
		$this->load->view('main/footer');
	}

	public function grains_pulses()
	{
		$this->load->model('products');
		$data['products'] = $this->products->productsByCategory('Grains & Rice');
		$data['category'] = 'Grains & Rice';
		$data['subcategories'] = $this->products->getSubcategories('Grains & Rice');
		
		$this->load->view('main/header');
		$this->load->view('pages/category_products', $data);
		$this->load->view('main/footer');
	}

	public function spices_condiments()
	{
		$this->load->model('products');
		$data['products'] = $this->products->productsByCategory('Spices & Seasonings');
		$data['category'] = 'Spices & Seasonings';
		$data['subcategories'] = $this->products->getSubcategories('Spices & Seasonings');
		
		$this->load->view('main/header');
		$this->load->view('pages/category_products', $data);
		$this->load->view('main/footer');
	}

	public function snacks_beverages()
	{
		$this->load->model('products');
		$data['products'] = $this->products->productsByCategory('Snacks & Instant Food');
		$data['category'] = 'Snacks & Instant Food';
		$data['subcategories'] = $this->products->getSubcategories('Snacks & Instant Food');
		
		$this->load->view('main/header');
		$this->load->view('pages/category_products', $data);
		$this->load->view('main/footer');
	}

	public function personal_care()
	{
		$this->load->model('products');
		$data['products'] = $this->products->productsByCategory('Personal Care');
		$data['category'] = 'Personal Care';
		$data['subcategories'] = $this->products->getSubcategories('Personal Care');
		
		$this->load->view('main/header');
		$this->load->view('pages/category_products', $data);
		$this->load->view('main/footer');
	}

	public function household_items()
	{
		$this->load->model('products');
		$data['products'] = $this->products->productsByCategory('Household Items');
		$data['category'] = 'Household Items';
		$data['subcategories'] = $this->products->getSubcategories('Household Items');
		
		$this->load->view('main/header');
		$this->load->view('pages/category_products', $data);
		$this->load->view('main/footer');
	}
	
	// Additional category methods for existing database categories
	public function cooking_oils()
	{
		$this->load->model('products');
		$data['products'] = $this->products->productsByCategory('Cooking Oils');
		$data['category'] = 'Cooking Oils';
		$data['subcategories'] = $this->products->getSubcategories('Cooking Oils');
		
		$this->load->view('main/header');
		$this->load->view('pages/category_products', $data);
		$this->load->view('main/footer');
	}
	
	public function beverages()
	{
		$this->load->model('products');
		$data['products'] = $this->products->productsByCategory('Beverages');
		$data['category'] = 'Beverages';
		$data['subcategories'] = $this->products->getSubcategories('Beverages');
		
		$this->load->view('main/header');
		$this->load->view('pages/category_products', $data);
		$this->load->view('main/footer');
	}
}

	