<?php

require_once('./application/third_party/httpful.phar');

// The host with searchd running
    const host = 'http://inet-sochi.ru:7003/';
// The label of search segment
    const _label = 'swf';
// synonyms use flag, 1 - to use, 0 - don't
    const _sy = 0;
// word forms use flag, 1 - to use, 0 - don't (search for words in query exactly)
    const _sp = 0;
// search mode, can be 'near', 'all', 'any'
    const _m = 'near';
// results groupping by site flag, 'yes' - to group, 'no' - don't
    const _GroupBySite = 'no';
// search result template 
    const _tmplt = 'json2.htm';
// search result ordering, 'I' - importance, 'R' - relevance, 'P' - PopRank, 'D' - date; use lower case letters for descending order
    const _s = 'IRPD';

class Pages extends CI_Controller {


	function search($search_terms = '', $start = 0)
	{
		// If the form has been submitted, rewrite the URL so that the search
		// terms can be passed as a parameter to the action. Note that there
		// are some issues with certain characters here.
		if ($this->input->post('q'))
		{
			redirect('/' . $this->input->post('q'));
		}
		
		if ($search_terms)
		{
			// Determine the number of results to display per page
			$results_per_page = $this->config->item('results_per_page');
			
			// Mark the start of search
//			$this->benchmark->mark('search_start');
			
			$page_num = $start/$results_per_page;

			$url = host . '?label=' . _label 
			    . '&ps=' . $results_per_page 
			    . '&np=' . $page_num
			    . '&sy=' . _sy 
			    . '&sp=' . _sp 
			    . '&m=' . _m 
			    . '&GroupBySite=' . _GroupBySite 
			    . '&tmplt=' . _tmplt 
			    . '&s=' . _s 
			    . '&q=' . urlencode($search_terms);

			$response = \Httpful\Request::get($url)
			    ->send();

			$result = $response->body->responseData;
			$results = $result->results;
			$total_results = $result->found;
			
			// Mark the end of search
//			$this->benchmark->mark('search_end');
			
			// Call a method to setup pagination
			$this->_setup_pagination('/' . $search_terms . '/', $total_results, $results_per_page);
			
			// Work out which results are being displayed
			$first_result = $start + 1;
			$last_result = min($start + $results_per_page, $total_results);
		}
		
		// Render the view, passing it the necessary data
		$this->load->view('search_results', array(
			'search_terms' => $search_terms,
			'first_result' => @$first_result,
			'last_result' => @$last_result,
			'total_results' => @$total_results,
			'results' => @$results
		));
		
		// Enable the profiler
//		$this->output->enable_profiler(TRUE);
	}
	
	/**
	 * Setup the pagination library.
	 *
	 * @param string $url The base url to use.
	 * @param string $total_results The total number of results.
	 * @param string $results_per_page The number of results per page.
	 * @return void
	 * @author Joe Freeman
	 */
	function _setup_pagination($url, $total_results, $results_per_page)
	{
		// Ensure the pagination library is loaded
		$this->load->library('pagination');
		
		// This is messy. I'm not sure why the pagination class can't work
		// this out itself...
		$uri_segment = count(explode('/', $url));
		
		// Initialise the pagination class, passing in some minimum parameters
		$this->pagination->initialize(array(
			'base_url' => site_url($url),
			'total_rows' => $total_results,
			'per_page' => $results_per_page
		));
	}
}

/* End of file pages.php */
/* Location: ./system/application/controllers/pages.php */