<?php
class ControllerExtensionPaymentPaystack extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/payment/paystack');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('payment_paystack', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('marketplace/extension', 'type=payment', true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['entry_public_key'] = $this->language->get('entry_public_key');
        $data['entry_secret_key'] = $this->language->get('entry_secret_key');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $keys = [
            'payment_paystack_public_key',
            'payment_paystack_secret_key',
            'payment_paystack_order_status_id',
            'payment_paystack_status',
            'payment_paystack_sort_order'
        ];

        foreach ($keys as $key) {
            if (isset($this->request->post[$key])) {
                $data[$key] = $this->request->post[$key];
            } else {
                $data[$key] = $this->config->get($key);
            }
        }

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['action'] = $this->url->link('extension/payment/paystack', '', true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'type=payment', true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/paystack', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/payment/paystack')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if (!$this->request->post['payment_paystack_public_key'] || !$this->request->post['payment_paystack_secret_key']) {
            $this->error['warning'] = $this->language->get('error_key');
        }
        return !$this->error;
    }
}