<?php
class ModelAccountInvoices extends Model {

        public function getTotalInvoices() {
                $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "cycling_invoices` o WHERE customer_id = '" . (int)$this->customer->getId() . "'");
                return $query->row['total'];
        }


        public function getInvoice($invoice_id) {
                $result = $this->db->query("SELECT o.*, os.name as status_id FROM `" . DB_PREFIX . "cycling_invoices` o LEFT JOIN " . DB_PREFIX . "cycling_invoices_status os ON (o.status_id = os.invoice_status_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "'");

//                $result= $this->db->query("SELECT * FROM `" . DB_PREFIX . "cycling_invoices` WHERE customer_id = '" . (int)$this->customer->getId() . "'");
                if ($result->num_rows) {
                  $this->load->language('account/invoices');
                  return array(
                                'order_id'   => $result->row['order_id'],
                                'invoiceNumber'   => $result->row['invoiceNumber'],
                                'factPeriod'   => $result->row['factPeriod'],
                                'status_id'     => $result->row['status_id'],
                                'txnid'     => $result->row['txnid'],
                                'date_added' => $result->row['date_added'],
                                'datePayed'  => $result->row['datePayed'],   
                                'dateExpire'  => $result->row['dateExpire'],
                                'amount'      => $result->row['amount'], 
                        );


                }
        }


	public function getInvoices($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 1;
		}
                $query = $this->db->query("SELECT o.*, os.name as status FROM `" . DB_PREFIX . "cycling_invoices` o LEFT JOIN " . DB_PREFIX . "cycling_invoices_status os ON (o.status_id = os.invoice_status_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.invoice_id DESC LIMIT " . (int)$start . "," . (int)$limit);




		return $query->rows;
	}


}
