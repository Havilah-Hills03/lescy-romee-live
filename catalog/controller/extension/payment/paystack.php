 <?php
class ControllerExtensionPaymentPaystack extends Controller {
    public function index() {
        $this->load->language('extension/payment/paystack');

        $data['public_key'] = $this->config->get('payment_paystack_public_key');
        $data['total'] = $this->cart->getTotal() * 100; // Paystack uses kobo

        return $this->load->view('extension/payment/paystack', $data);
    }

    public function callback() {
        $reference = $this->request->get['reference'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.paystack.co/transaction/verify/" . $reference);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $this->config->get('payment_paystack_secret_key')
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if ($result['data']['status'] == 'success') {
            // Payment successful, update order status
            $this->load->model('checkout/order');
            $order_id = $this->session->data['order_id'];
            $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_paystack_order_status_id'));
            $this->response->redirect($this->url->link('checkout/success'));
        } else {
            $this->response->redirect($this->url->link('checkout/checkout', '', true));
        }
    }
}