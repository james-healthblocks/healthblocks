import { Component } from '@angular/core';
import { NavController, NavParams, MenuController } from 'ionic-angular';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Data } from '../../providers/data';
import { Landing } from '../landing/landing'

import { DatePipe } from '@angular/common';
import {RegisterService} from '../../providers/register-service';

/*
  Generated class for the Registration page.

  See http://ionicframework.com/docs/v2/components/#navigation for more info on
  Ionic pages and navigation.
*/
@Component({
  selector: 'page-registration',
  templateUrl: 'registration.html',
  providers: [RegisterService]
})
export class Registration {

	fields=[];
	uic: string; 
	profileData=[];
	registrationForm: FormGroup;
	UICstar: string;
	
	constructor(private navCtrl: NavController, public navParams: NavParams, public menu: MenuController, public formBuilder: FormBuilder, public dataService: Data, private datePipe: DatePipe, public registerService: RegisterService) {
		this.navCtrl = navCtrl;
		this.menu = menu;
		this.menu.enable(false, "appMenu");

		this.registrationForm = formBuilder.group({
			Philhealth: ['', Validators.compose([
				Validators.required,
				Validators.maxLength(12),
				Validators.minLength(12),
				Validators.pattern('^\\d+$'),

			])],
			FirstName: [''], //, Validators.compose([Validators.required])],
			MiddleName: [''],
			LastName: [''], // Validators.compose([Validators.required])],
			ExtensionName: [''],
			PresentAddress: [''],
			PermanentAddress: [''],
			Birthdate: [''],//, Validators.compose([Validators.required])],
			MotherFirstName: [''],//, Validators.compose([Validators.required])],
			MotherLastName: [''],//, Validators.compose([Validators.required])],
			FatherFirstName: [''],//, Validators.compose([Validators.required])],
			FatherLastName: [''],//, Validators.compose([Validators.required])],
			BirthOrder: ['', Validators.compose([
				Validators.required,
				Validators.maxLength(2),
				Validators.pattern('^\\d+$'),
			])],
			SpouseName: [''],
			ChildName1: [''],
			ChildName2: [''],
			ChildName3: [''],
			ChildName4: [''],
			ChildName5: [''],
		});
		
		// Fields 
		this.fields = [
			{label:'Philhealth No. *', name:'Philhealth', type:'tel'},
			{label:'First Name *', name:'FirstName', type:'text'},
			{label:'Middle Name', name:'MiddleName', type:'text'},
			{label:'Last Name *', name:'LastName', type:'text'},
			{label:'Extension Name', name:'ExtensionName', type:'text'},
			{label:'Present Address', name:'PresentAddress', type:'text'},
			{label:'Permanent Address', name:'PermanentAddress', type:'text'},
			{label:'Birthdate *', name:'Birthdate', type: 'date'},
			
			{label:'Mother First Name *', name:'MotherFirstName', type:'text'}, //9
			{label:'Mother Last Name *', name:'MotherLastName', type:'text'},
			{label:'Father First Name *', name:'FatherFirstName', type:'text'},
			{label:'Father Last Name *', name:'FatherLastName', type:'text'},
			{label:'Birth Order *', name:'BirthOrder', type:'number'},
			{label:'Spouse Name', name:'SpouseName', type:'text'},
			{label:'Child Name 1', name:'ChildName1', type:'text'},
			{label:'Child Name 2', name:'ChildName2', type:'text'},
			{label:'Child Name 3', name:'ChildName3', type:'text'},
			{label:'Child Name 4', name:'ChildName4', type:'text'},
			{label:'Child Name 5', name:'ChildName5', type:'text'},
		]
	}
  
	register() {
		if (this.registrationForm.valid) {
			this.generateUIC();

			this.registerService.register(this.UICstar)
			.then(data => {
				let parsed = JSON.parse(JSON.stringify(data));

				if (parsed.status === "success") {
					// TODO: store wallet address somewhere
					this.dataService.setWalletAddress(parsed.wallet_address);
					console.log("success na", parsed.wallet_address);
				}
				else if (parsed.status === "failed") {
					console.log("status: failed")
				}

				this.save();
				this.navCtrl.setRoot(Landing, {uic: this.UICstar});
			}, error => {
				console.log("status: error");
			});
		}
		else {
			console.log("invalid form!");
		}

	}
	
	save() {
		//Save in local storage
		let regform = this.registrationForm.value;
		regform['UIC'] = this.UICstar;
		
		console.log(this.UICstar);
		
		this.dataService.setUIC(this.UICstar);
		this.dataService.saveProfile(this.UICstar, regform);
		//this.dataService.setUIC(this.UICstar);
	}	

	generateUIC() {
		console.log("generate UIC method");

		let regform = this.registrationForm.value;

		// let UICstar = regform.Philhealth +
		let UICstar = 
			regform.MotherFirstName.charAt(0) + regform.MotherLastName.charAt(0) +
			regform.FatherFirstName.charAt(0) + regform.FatherLastName.charAt(0) +
			((regform.BirthOrder > 9) ? "" + regform.BirthOrder: "0" + regform.BirthOrder) +
			this.datePipe.transform(regform.Birthdate, 'MMddyyyy');

		console.log("UICstar", UICstar.toUpperCase());
		this.UICstar = UICstar.toUpperCase();
		
	}

	ionViewDidLoad() {
		console.log('ionViewDidLoad RegistrationPage');
	}

}
