<?php

require_once 'App.php';

class CustomerVerification extends App {
    protected $table = 'customer_verifications';

    public $id;
    public $orderId;
    public $customerContact;
    public $verificationCode;
    public $verifiedAt;
    public $attempts;

    public function __construct($id = null) {
        parent::__construct();
        if ($id) {
            $this->id = $id;
            $this->load();
        }
    }

    private function load() {
        $stmt = $this->conn->prepare("SELECT * FROM customer_verifications WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->orderId = $row['order_id'];
            $this->customerContact = $row['customer_contact'];
            $this->verificationCode = $row['verification_code'];
            $this->verifiedAt = $row['verified_at'];
            $this->attempts = $row['attempts'];
        } else {
            self::returnError('HTTP/1.1 404', 'Load CustomerVerification Error: CustomerVerification [id = ' . $this->id . '] does not exist.');
        }
        $stmt->close();
    }

    public function save() {
        if ($this->id) {
            $stmt = $this->conn->prepare("UPDATE customer_verifications SET order_id = ?, customer_contact = ?, verification_code = ?, verified_at = ?, attempts = ? WHERE id = ?");
            $stmt->bind_param("issiii", $this->orderId, $this->customerContact, $this->verificationCode, $this->verifiedAt, $this->attempts, $this->id);
            $stmt->execute();
            if ($stmt->affected_rows === 0) {
                self::returnError('HTTP/1.1 404', 'Update CustomerVerification Error: No CustomerVerification updated or CustomerVerification [id = ' . $this->id . '] does not exist.');
            }
        } else {
            $stmt = $this->conn->prepare("INSERT INTO customer_verifications (order_id, customer_contact, verification_code, verified_at, attempts) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issii", $this->orderId, $this->customerContact, $this->verificationCode, $this->verifiedAt, $this->attempts);
            $stmt->execute();
            if ($stmt->affected_rows === 0) {
                self::returnError('HTTP/1.1 400', 'Create CustomerVerification Error: Unable to create CustomerVerification.');
            }
            $this->id = $this->conn->insert_id;
        }
        $stmt->close();
    }

    public function delete() {
        if (!$this->id) {
            self::returnError('HTTP/1.1 404', 'Delete CustomerVerification Error: CustomerVerification ID is missing.');
        }
        $stmt = $this->conn->prepare("DELETE FROM customer_verifications WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        if ($stmt->affected_rows === 0) {
            self::returnError('HTTP/1.1 404', 'Delete CustomerVerification Error: CustomerVerification [id = ' . $this->id . '] does not exist.');
        }
        $stmt->close();
    }

    public function order() {
        require_once 'Order.php';
        return new Order($this->orderId);
    }
}
