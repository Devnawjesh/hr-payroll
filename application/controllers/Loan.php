<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('login_model');
		$this->load->model('dashboard_model');
		$this->load->model('employee_model');
		$this->load->model('loan_model');
		$this->load->model('settings_model');
		$this->load->model('leave_model');
	}

	public function View()
	{
		if ($this->session->userdata('user_login_access') != false) {
			$data['employee'] = $this->employee_model->emselect();
			$data['loanview'] = $this->loan_model->loan_modeldata();
			$this->load->view('backend/loan', $data);
		} else {
			redirect(base_url(), 'refresh');
		}
	}
	public function Add_Loan()
	{
		if ($this->session->userdata('user_login_access') != false) {
			$id = $this->input->post('id');
			$em_id = $this->input->post('emid');
			$details = $this->input->post('details');
			$appdate = $this->input->post('appdate');
			$amount = $this->input->post('amount');
			/*$interest = $this->input->post('interest');
        $interestper = $this->input->post('interest')/100;*/
			$install = $this->input->post('install');
			$status = $this->input->post('status');
			$loanno = $this->input->post('loanno');
			/* $total = $this->input->post('amount') * $interestper;*/
			/*$totalamount = $amount + $total;*/
			$installment = round($this->input->post('installment'));
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters();
			$this->form_validation->set_rules('details', 'Loan details', 'trim|required|min_length[10]|max_length[220]|xss_clean');

			if ($this->form_validation->run() == false) {
				echo validation_errors();
				#redirect("loan/View");
			} else {
				#$emvalue = $this->loan_model->GetEmployeeForloancheck($em_id);
				#echo $emvalue->status;
				$data = array();
				$data = array(
					'emp_id' => $em_id,
					'loan_details' => $details,
					'approve_date' => $appdate,
					'amount' => $amount,
					/*'interest_percentage' => $interest,*/
					'install_period' => $install,
					'installment' => $installment,
					/*'total_amount' => $totalamount,*/
					'total_pay' => '0',
					'total_due' => '0',
					'status' => $status,
					'loan_number' => $loanno
				);
				if (empty($id)) {
					$emvalue = $this->loan_model->GetEmployeeForloancheck($em_id);
					#echo $emvalue->status;
					if (!empty($emvalue->status)) {
						echo "Already you have a loan. Please pay installation first";
					} else {
						$success = $this->loan_model->Add_LoanData($data);
						echo "Successfully Added";
					}
				} else {
					$success = $this->loan_model->update_LoanDataVal($id, $data);
					echo "Successfully Updated";
				}
			}
		} else {
			redirect(base_url(), 'refresh');
		}
	}
	public function installment()
	{
		if ($this->session->userdata('user_login_access') != false) {
			$data['employee'] = $this->employee_model->emselect();
			$data['installment'] = $this->loan_model->installmentSelect();
			$this->load->view('backend/loan_installment', $data);
		} else {
			redirect(base_url(), 'refresh');
		}
	}
	public function Add_Loan_Installment()
	{
		if ($this->session->userdata('user_login_access') != false) {
			$id = $this->input->post('id');
			$em_id = $this->input->post('emid');
			$loanid = $this->input->post('loanid');
			$loanno = $this->input->post('loanno');
			$amount = $this->input->post('amount');
			$appdate = $this->input->post('appdate');
			$receiver = $this->input->post('receiver');
			$installno = $this->input->post('installno');
			$notes = $this->input->post('notes');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters();
			$this->form_validation->set_rules('notes', 'Loan details', 'trim|required|min_length[10]|max_length[220]|xss_clean');

			if ($this->form_validation->run() == false) {
				echo validation_errors();
				#redirect("loan/View");
			} else {
				if (empty($id)) {
					$loanvalue = $this->loan_model->GetLoanValuebyLId($loanid);
					$period = $loanvalue->install_period - 1;
					$data = array();
					$data = array(
						'emp_id' => $em_id,
						'loan_id' => $loanid,
						'loan_number' => $loanno,
						'install_amount' => $amount,
						/*'pay_amount' => $payment,*/
						'app_date' => $appdate,
						'receiver' => $receiver,
						'install_no' => $period,
						'notes' => $notes
					);
					$success = $this->loan_model->Add_installData($data);
					$totalpay = $loanvalue->total_pay + $amount;
					$totaldue = $loanvalue->amount - $totalpay;
					/*$period = $loanvalue->install_period - 1;*/
					if ($installno == '1') {
						$status = 'Done';
					} else {
						$status = 'Granted';
					}
					$data = array();
					$data = array(
						'total_pay'=>$totalpay,
						'total_due'=>$totaldue,
						'install_period'=>$period,
						'status'=>$status
					);
					$success = $this->loan_model->update_LoanData($loanid, $data);
					echo "Successfully Added";
				} else {
					$data = array();
					$data = array(
						'emp_id' => $em_id,
						'loan_id' => $loanid,
						'loan_number' => $loanno,
						'install_amount' => $amount,
						/*'pay_amount' => $payment,*/
						'app_date' => $appdate,
						'receiver' => $receiver,
						/*'install_no' => $period,*/
						'notes' => $notes
					);
					$success = $this->loan_model->update_LoanInstallData($id, $data);
					echo "Successfully Updated";
				}
			}
		} else {
			redirect(base_url(), 'refresh');
		}
	}
	public function LoanByID()
	{
		if ($this->session->userdata('user_login_access') != false) {
			$id = $this->input->get('id');
			$data['loanvalue'] = $this->loan_model->LoanValselect($id);
			$data['loanvalueem'] = $this->loan_model->LoanValEmselect($id);
			$data['loanvalueinstallment'] = $this->loan_model->LoanInstallValEmselect($id);
			echo json_encode($data);
		} else {
			redirect(base_url(), 'refresh');
		}
	}
}
?>