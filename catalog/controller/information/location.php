<?php
namespace Opencart\Catalog\Controller\Information;

class Location extends \Opencart\System\Engine\Controller {

    public function index(): void {

        $this->document->setTitle('Locations');

        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput(
            $this->load->view('information/location', $data)
        );
    }
}
