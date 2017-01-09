import { Component } from '@angular/core';
import { NavController, NavParams, ViewController } from 'ionic-angular';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Data } from '../../providers/data';

@Component({
  selector: 'page-insurance',
  templateUrl: 'insurance.html'
})
export class Insurance {
  
  uic: string; 
  label: string;
  fields = [];
  insuranceList = [];
  insuranceForm: FormGroup;
  
  constructor(public navCtrl: NavController, public navParams: NavParams, public viewCtrl: ViewController, public formBuilder: FormBuilder, public dataService: Data) {
	
	//Form
	this.insuranceForm = formBuilder.group({
		id: [''],
		InsuranceName: [''],
		InsuranceNumber: [''],
		FirstName: [''],
		MiddleName: [''],
		LastName: [''],
		ExtensionName: [''],
		PlanType: [''],
		CoverageDetails: [''],
		BenefitLimitIndicator: ['']
	});
	
	//Fields
	this.fields = [
		{label: 'id', name:'id', type:'hidden'},
		{label:'Insurance Name', name:'InsuranceName', type:'text'},
		{label:'Insurance Number', name:'InsuranceNumber', type:'text'},
		{label:'First Name', name:'FirstName', type:'text'},
		{label:'Middle Name', name:'MiddleName', type:'text'},
		{label:'Last Name', name:'LastName', type:'text'},
		{label:'Extension Name', name:'ExtensionName', type:'text'},
		{label:'Plan Type', name:'PlanType', type:'text'},
		{label:'Coverage Details', name:'CoverageDetails', type:'text'},
		{label:'Benefit Limit Indicator', name:'BenefitLimitIndicator', type:'text'}
	];
  }
  
  ionViewDidLoad(){	
	this.uic = this.navParams.get('uic');
	
	if(this.navParams.get('insurance_item') !== undefined) {
		
		var insurance_item = this.navParams.get('insurance_item');
		for (var k in insurance_item) {
			if (insurance_item.hasOwnProperty(k)) {
			    this.insuranceForm.controls[k].setValue(insurance_item[k]);
			}
		}
	}
	else {
		var m = 0;
		this.insuranceForm.controls['id'].setValue(1);
		
		//Get list of insurances
		this.dataService.getInsuranceList(this.uic).then((insuranceList) => {
		if(insuranceList){
			this.insuranceList = JSON.parse(insuranceList); 
			for (var i = 0; i < this.insuranceList.length; i++) {
				
				if(parseInt(this.insuranceList[i].id) > m) {
					m = parseInt(this.insuranceList[i].id);
				}
			}
			
			//Increment id by 1
			m = m+1; 
			this.insuranceForm.controls['id'].setValue(m);
		}});
		
	}
  }
  
  save() {
	this.insuranceList.push(this.insuranceForm.value);
	this.dataService.saveInsurance(this.uic, this.insuranceList);
	this.viewCtrl.dismiss(this.insuranceForm.value);
  }
  
  close() {
	  this.viewCtrl.dismiss();
  }

}
