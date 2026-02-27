<?php
namespace Opencart\Catalog\Controller\About;

class About extends \Opencart\System\Engine\Controller {
    public function index(): void {

        $this->document->setTitle('About Us');

        $data['heading_title'] = 'About Us';
        $data['message'] = 'Welcome to our restaurant. We serve the best meals in town!';

        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('about/about', $data));
    }
}
