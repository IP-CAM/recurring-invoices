<?php
class ControllerAccountInvoices extends Controller {
	public function index() {
                unset($this->session->data['invoice_to_pay']);
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/invoices', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		//$this->load->language('account/order');
                $this->load->language('account/invoices');
		$this->document->setTitle($this->language->get('heading_title'));
		
		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/invoices', $url, true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_empty'] = $this->language->get('text_empty');

		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
                $data['column_date_expire'] = $this->language->get('column_date_expire');
                $data['column_date_payed'] = $this->language->get('column_date_payed');
                $data['column_facturation_period'] = $this->language->get('column_facturation_period');
                $data['column_date_added'] = $this->language->get('column_date_added');
                $data['column_invoice_number'] = $this->language->get('column_invoice_number');
                $data['column_facturation_period'] = $this->language->get('column_facturation_period');
                $data['column_txn'] = $this->language->get('column_txn');
		$data['button_view'] = $this->language->get('button_view');
		$data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['invoices'] = array();

		$this->load->model('account/invoices');
                $this->load->model('account/order');
		$invoices_total = $this->model_account_invoices->getTotalInvoices();
		$results = $this->model_account_invoices->getInvoices(($page - 1) * 10, 10);

		foreach ($results as $result) {
			$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
			$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);
                        $order_info=$this->model_account_order->getOrder($result['order_id']);
                  
                        $fact_period = $result['factPeriod'];
                        $split_date = explode(" - ",$fact_period);
                        $date1 = date($this->language->get('date_format_short'), strtotime($split_date[0]));
                        $date2 = date($this->language->get('date_format_short'), strtotime($split_date[1]));

			$data['invoices'][] = array(
				'order_id'   => $result['order_id'],
                                'total_invoices' => $invoices_total,
                                'invoiceNumber'   => $result['invoiceNumber'],
                                'factPeriod'   => $date1 . ' - ' . $date2,
                                'invoice_status_id' => $result['status_id'],
				'status'     => $result['status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                                'datePayed'  => date($this->language->get('date_format_short'), strtotime($result['datePayed'])),   
                                'dateExpire'  => date($this->language->get('date_format_short'), strtotime($result['dateExpire'])),
				'products'   => ($product_total + $voucher_total),
				'amount'      => $this->currency->format($result['amount'], $order_info['currency_code'], $order_info['currency_value']),
				'view'       => $this->url->link('account/invoices/info', 'invoice_id=' . $result['invoice_id'], true),
			);
		}

		$pagination = new Pagination();
		$pagination->total = $invoices_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/invoices', 'page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($invoices_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($invoices_total- 10)) ? $invoices_total : ((($page - 1) * 10) + 10), $invoices_total, ceil($invoices_total / 10));

		$data['continue'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/invoices_list', $data));
	}

	public function info() {
		$this->load->language('account/invoices');

		if (isset($this->request->get['invoice_id'])) {
			$invoice_id = $this->request->get['invoice_id'];
		} else {
			$invoice_id = 0;
		}

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/invoices/info', 'invoice_id=' . $invoice_id, true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->model('account/invoices');
                $this->load->model('account/order');
		$invoice_info = $this->model_account_invoices->getInvoice($invoice_id);
                $order_id = $invoice_info['order_id'];
                $order_info = $this->model_account_order->getOrder($order_id);
                $fact_period = $invoice_info['factPeriod'];
                $split_date = explode(" - ",$fact_period);
                $date1 = date($this->language->get('date_format_short'), strtotime($split_date[0]));
                $date2 = date($this->language->get('date_format_short'), strtotime($split_date[1]));
                //Invoice Data
                $data['invoice_id'] = $invoice_id;
                $data['invoiceNumber'] = $invoice_info['invoiceNumber'];
                $data['factPeriod'] = $date1 . ' - ' . $date2;
                $data['date_added'] = date($this->language->get('date_format_short'), strtotime($invoice_info['date_added']));
                $data['datePayed'] = date($this->language->get('date_format_short'), strtotime($invoice_info['datePayed']));
                $data['dateExpire'] = date($this->language->get('date_format_short'), strtotime($invoice_info['dateExpire']));
                $data['amount'] = $this->currency->format($invoice_info['amount'], $order_info['currency_code'], $order_info['currency_value']);
                $data['status_id'] = $invoice_info['status_id'];
                $data['status'] = $invoice_info['status'];
                $data['order_id'] = $invoice_info['order_id'];
                $data['txnid'] = $invoice_info['txnid'];

                
                if ($data['status_id'] == '1' ) { 
                  $data['datePayed']='';
                  $this->session->data['invoice_to_pay'] = $data;
                }


		if ($invoice_info) {
			$this->document->setTitle($this->language->get('text_invoice'));

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/invoices', $url, true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_invoice'),
				'href' => $this->url->link('account/invoices/info', 'invoice_id=' . $this->request->get['invoice_id'] . $url, true)
			);

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_invoice_detail'] = $this->language->get('text_invoice_detail');
			$data['text_invoice_no'] = $this->language->get('text_invoice_no');
			$data['text_order_id'] = $this->language->get('text_order_id');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$data['text_shipping_address'] = $this->language->get('text_shipping_address');
			$data['text_payment_method'] = $this->language->get('text_payment_method');
			$data['text_payment_address'] = $this->language->get('text_payment_address');
			$data['text_history'] = $this->language->get('text_history');
			$data['text_comment'] = $this->language->get('text_comment');
			$data['text_no_results'] = $this->language->get('text_no_results');
                        $data['text_tax_number'] =  $this->language->get('text_tax_number');
                        $data['text_facturation_period'] = $this->language->get('text_facturation_period');
                  
                        $data['text_confirm_payment_method'] = $this->language->get('text_confirm_payment_method');
			$data['column_name'] = $this->language->get('column_name');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');
			$data['column_action'] = $this->language->get('column_action');
			$data['column_date_added'] = $this->language->get('column_date_added');
			$data['column_status'] = $this->language->get('column_status');
			$data['column_comment'] = $this->language->get('column_comment');
                        $data['column_txn'] = $this->language->get('column_txn');
                        $data['column_date_payed'] = $this->language->get('column_date_payed');

			$data['button_reorder'] = $this->language->get('button_reorder');
			$data['button_return'] = $this->language->get('button_return');
			$data['button_continue'] = $this->language->get('button_continue');
                        $data['text_back'] = $this->language->get('text_back');

                        //Store Data
                        $data['store_name'] = $this->config->get('config_name');
                        $data['store_owner'] = $this->config->get('config_owner');
                        $data['store_address'] = $this->config->get('config_address');
                        $data['store_zip'] = $this->config->get('config_geocode');
                        $data['store_telephone'] = $this->config->get('config_telephone');


                        $server = $this->config->get('config_url');
                        $data['store_logo'] = $server . 'image/' . $this->config->get('config_logo');
                        
                        if (isset($this->session->data['payment_method']['code'])) {
                            $data['code'] = $this->session->data['payment_method']['code'];
                        } else {
                            $data['code'] = '';
                        }

            

			if (isset($this->session->data['error'])) {
				$data['error_warning'] = $this->session->data['error'];

				unset($this->session->data['error']);
			} else {
				$data['error_warning'] = '';
			}

			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}
                      
			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}
			//$data['invoice_id'] = $invoice_info['invoiceNumber'];;
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

			if ($order_info['payment_address_format']) {
				$format = $order_info['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

                        //Use default address fro invoices
                        $this->load->model('account/address');
                        $address_id = $this->getdefaultAddressId();
                        $address_id = $address_id->row['address_id'];
                        $default_addres= $this->model_account_address->getAddress($address_id);
			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $default_addres['firstname'],
				'lastname'  => $default_addres['lastname'],
				'company'   => $default_addres['company'],
				'address_1' => $default_addres['address_1'],
				'address_2' => $default_addres['address_2'],
				'city'      => $default_addres['city'],
				'postcode'  => $default_addres['postcode'],
				'zone'      => $default_addres['zone'],
				'zone_code' => $default_addres['zone_code'],
				'country'   => $default_addres['country']
			);

			$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));


			$data['order_payment_method'] = $order_info['payment_method'];

			if ($order_info['shipping_address_format']) {
				$format = $order_info['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $order_info['shipping_firstname'],
				'lastname'  => $order_info['shipping_lastname'],
				'company'   => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
				'country'   => $order_info['shipping_country']
			);

			$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$data['shipping_method'] = $order_info['shipping_method'];

			$this->load->model('catalog/product');
			$this->load->model('tool/upload');

			// Products
			$data['products'] = array();

			$products = $this->model_account_order->getOrderProducts($order_id);

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}

				$product_info = $this->model_catalog_product->getProduct($product['product_id']);

				if ($product_info) {
/*
					$reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], true);
				} else {
					$reorder = '';
*/
                                $data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($product['product_id']);
				}

				$data['products'][] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					//'reorder'  => $reorder,
					'return'   => $this->url->link('account/return/add', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], true),
                                        //We don't use recurring payments like that. Need to set nar inr order to use confirm.tpl
                                        'recurring' => '',
                                        'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id'])
				);
			}



			// Voucher
			$data['vouchers'] = array();

			$vouchers = $this->model_account_order->getOrderVouchers($order_id);

			foreach ($vouchers as $voucher) {
				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			// Totals
			$data['totals'] = array();

			$totals = $this->model_account_order->getOrderTotals($order_id);

			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
				);
			}



			$data['comment'] = nl2br($order_info['comment']);



			// History
			$data['histories'] = array();

			$results = $this->model_account_order->getOrderHistories($order_id);

			foreach ($results as $result) {
				$data['histories'][] = array(
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'status'     => $result['status'],
					'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
				);
			}



                        // Dont' really Know what am I doing
                unset($this->session->data['payment_address']);
                $this->load->language('checkout/checkout');
                $this->session->data['payment_address'] = $this->model_account_address->getAddress($address_id);
                if (isset($this->session->data['payment_address'])) {
                        // Totals
                        $totals = array();
                        $taxes = $this->getTaxes($products);
                        //Number of addresses?
                        $totala = 1;
                  
                        // Because __call can not keep var references so we put them into an array.
                        $total_data = array(
                                'totals' => &$totals,
                                'taxes'  => &$taxes,
                                'total'  => &$totala
                        );
                        $this->load->model('extension/extension');

                        $sort_order = array();

                        $results = $this->model_extension_extension->getExtensions('total');
                        foreach ($results as $key => $value) {
                                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
                        }

                        array_multisort($sort_order, SORT_ASC, $results);

                        foreach ($results as $result) {
                                if ($this->config->get($result['code'] . '_status')) {
                                        $this->load->model('extension/total/' . $result['code']);

                                        // We have to put the totals in an array so that they pass by reference.
                                        $this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
                                }
                        }


                        // Payment Methods
                        $method_data = array();

                        $this->load->model('extension/extension');

                        $results = $this->model_extension_extension->getExtensions('payment');
                        $recurring = $this->cart->hasRecurringProducts();
                       // $this->session->data['payment_address']['country_id'] = $this->session->data['shipping_address']['country_id'];
                       // $this->session->data['payment_address']['zone_id'] = $this->session->data['shipping_address']['zone_id'];
                        foreach ($results as $result) {
                                if ($this->config->get($result['code'] . '_status')) {
                                        $this->load->model('extension/payment/' . $result['code']);

                                        $method = $this->{'model_extension_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $totala);
                                        if ($method) {
                                                if ($recurring) {
                                                        if (property_exists($this->{'model_extension_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_extension_payment_' . $result['code']}->recurringPayments()) {
                                                                $method_data[$result['code']] = $method;
                                                        }
                                                } else {
                                                        $method_data[$result['code']] = $method;
                                                }
                                        }
                                }
                        }

                        $sort_order = array();
                        foreach ($method_data as $key => $value) {
                                $sort_order[$key] = $value['sort_order'];
                        }

                        array_multisort($sort_order, SORT_ASC, $method_data);

                        $this->session->data['payment_methods'] = $method_data;
                        $data['payment_methods'] = $method_data;
                }


                        if ($data['status_id'] == '1' ) {
                          $this->session->data['invoice_to_pay']['vouchers'] = $data['vouchers'];
                          $this->session->data['invoice_to_pay']['products'] = $data['products'];
                          $this->session->data['invoice_to_pay']['totals'] = $data['totals'];
                        }




			$data['continue'] = $this->url->link('account/invoices', '', true);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
                       
                        $data['gateway'] = $this->load->controller('extension/payment/gateway');
			$this->response->setOutput($this->load->view('account/invoices_info', $data));
		} else {
			$this->document->setTitle($this->language->get('text_order'));

			$data['heading_title'] = $this->language->get('text_order');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');
                        $data['text_back'] = $this->language->get('text_back');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/invoices', '', true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_order'),
				'href' => $this->url->link('account/invoices/info', 'order_id=' . $order_id, true)
			);

			$data['continue'] = $this->url->link('account/invoices', '', true);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
                      
                       // $data['payment'] = $this->load->controller('extension/payment/' . $this->session->data['payment_method']['code']); 

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}



        //Get taxes for current Invoice (the default getTaxes looks into cart
        public function getTaxes($products) {

                $tax_data = array();

                foreach ($products as $product) {
                                $product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store p2s LEFT JOIN " . DB_PREFIX . "product p ON (p2s.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p2s.product_id = '" . (int)$product['product_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p.status = '1'");
                        //var_dump ($product_query);      
                        if ($product_query->num_rows) {
                                
                                $tax_rates = $this->cart->tax->getRates($product_query->row['price'], $product_query->row['tax_class_id']);
                                foreach ($tax_rates as $tax_rate) {
                                        if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
                                                $tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
                                        } else {
                                                $tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
                                        }
                                }
                        }
                }

                return $tax_data;
        }

        public function getdefaultAddressId(){
           $address_id = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "customer  WHERE customer_id = '" . (int)$this->customer->getId() . "'"); 
        return $address_id;

        }
          

}
