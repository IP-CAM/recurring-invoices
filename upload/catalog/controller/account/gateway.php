<?php
class ControllerAccountGateway extends Controller {
	public function index() {

                if (isset($this->request->get['invoice_id'])) {
                        $invoice_id = $this->request->get['invoice_id'];
                } else {
                        $invoice_id = 0;
                }

                $redirect='';
                $recurring = '';
                $this->load->language('account/invoices');

                $data=$this->session->data['invoice_to_pay'];
                $data['column_name'] = $this->language->get('column_name');
                $data['column_model'] = $this->language->get('column_model');
                $data['column_quantity'] = $this->language->get('column_quantity');
                $data['column_price'] = $this->language->get('column_price');
                $data['column_total'] = $this->language->get('column_total');
                $data['invoice'] =  $invoice_id;
                // Validate if payment method has been set.
                if (!isset($this->session->data['payment_method'])) {
                  $this->url->link('account/invoices/info', 'invoice_id=' . $invoice_id, true);

                }
                $this->session->data['order_id'] = $this->session->data['invoice_to_pay']['order_id'];
                $data['payment'] = $this->load->controller('extension/payment/' . $this->session->data['payment_method']['code']);

		//$this->response->setOutput($this->load->view('checkout/payment_method', $data));
               $this->response->setOutput($this->load->view('checkout/confirm', $data));
                }
	public function save() {

                unset($this->session->data['payment_method']);
		$this->load->language('checkout/checkout');

		$json = array();

		// Validate if payment address has been set.
		if (!isset($this->session->data['payment_address'])) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', true);
		}


		// Validate minimum quantity requirements.

		if (!isset($this->request->post['payment_method'])) {
			$json['error']['warning'] = $this->language->get('error_payment');
		} elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
			$json['error']['warning'] = $this->language->get('error_payment');
		}


		if (!$json) {
			$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
			//$this->session->data['comment'] = strip_tags($this->request->post['comment']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
