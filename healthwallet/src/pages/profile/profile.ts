import { Component } from '@angular/core';
import { NavController, NavParams } from 'ionic-angular';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Data } from '../../providers/data';

@Component({
  selector: 'page-profile',
  templateUrl: 'profile.html'
})
export class Profile {
	
	uic: string;
	fields=[];
	profileData=[];
	profileForm: FormGroup;
	
	constructor(private navCtrl: NavController, public navParams: NavParams, public formBuilder: FormBuilder, public dataService: Data) {
		this.navCtrl = navCtrl;
		
		//Form
		this.profileForm = formBuilder.group({
			UIC: [''],
			Philhealth: [''],
			FirstName: ['', Validators.compose([Validators.required])],
			MiddleName: [''],
			LastName: ['', Validators.compose([Validators.required])],
			ExtensionName: [''],
			PresentAddress: [''],
			PermanentAddress: [''],
			Birthdate: ['', Validators.compose([Validators.required])],
			MotherFirstName: ['', Validators.compose([Validators.required])],
			MotherLastName: ['', Validators.compose([Validators.required])],
			FatherFirstName: ['', Validators.compose([Validators.required])],
			FatherLastName: ['', Validators.compose([Validators.required])],
			BirthOrder: ['', Validators.compose([Validators.required])],
			SpouseName: [''],
			ChildName1: [''],
			ChildName2: [''],
			ChildName3: [''],
			ChildName4: [''],
			ChildName5: [''],
			BloodType: [''],
			Allergies: [''],
			CurrentMedications: [''],
			PreviousMedications: [''],
			PreviousDiseases: [''],
			PreviousSurgeries: [''],
			HereditaryDiseases: ['']
		});
		
		//Fields 
		this.fields = [
			{label:'UIC', name:'UIC', type:'text'},
			{label:'Philhealth', name:'Philhealth', type:'text'},
			{label:'First Name', name:'FirstName', type:'text'},
			{label:'Middle Name', name:'MiddleName', type:'text'},
			{label:'Last Name', name:'LastName', type:'text'},
			{label:'Extension Name', name:'ExtensionName', type:'text'},
			{label:'Present Address', name:'PresentAddress', type:'text'},
			{label:'Permanent Address', name:'PermanentAddress', type:'text'},
			{label:'Birthdate ', name:'Birthdate', type: 'date'},
			
			{label:'Mother First Name', name:'MotherFirstName', type:'text'}, //9
			{label:'Mother Last Name', name:'MotherLastName', type:'text'},
			{label:'Father First Name', name:'FatherFirstName', type:'text'},
			{label:'Father Last Name', name:'FatherLastName', type:'text'},
			{label:'Birth Order', name:'BirthOrder', type:'text'},
			{label:'Spouse Name', name:'SpouseName', type:'text'},
			{label:'Child Name 1', name:'ChildName1', type:'text'},
			{label:'Child Name 2', name:'ChildName2', type:'text'},
			{label:'Child Name 3', name:'ChildName3', type:'text'},
			{label:'Child Name 4', name:'ChildName4', type:'text'},
			{label:'Child Name 5', name:'ChildName5', type:'text'},
			
			{label:'Blood Type', name:'BloodType', type:'text'}, //20
			{label:'Allergies', name:'Allergies', type:'text'},
			{label:'Current Medications', name:'CurrentMedications', type:'text'},
			{label:'Previous Medications', name:'PreviousMedications', type:'text'},
			{label:'Previous Diseases', name:'PreviousDiseases', type:'text'},
			{label:'Previous Surgeries', name:'PreviousSurgeries', type:'text'},
			{label:'Hereditary Diseases', name:'HereditaryDiseases', type:'text'}
		];
		
		/*//Get data from local storage
		this.dataService.getProfile(this.uic).then((profileData) => {
			if(profileData) {
				this.profileData = JSON.parse(profileData); 
				
				for(var k in this.profileData)
				{	//Set value in the form  
					if (this.profileData.hasOwnProperty(k)) {
						this.profileForm.controls[k].setValue(this.profileData[k]);
					}
				}
				
			}
		});*/
	}
	
	ionViewDidLoad(){
		let uic = this.navParams.get('uic');

		if(uic) {
			this.getProfile(uic);
		}
		else {
			this.dataService.getUIC().then((uicstar) => {
				if(uicstar) {
					this.getProfile(uicstar);
				}
			});
		}
			
		
	}
	
	getProfile(uic) {
		//Get data from local storage
		this.dataService.getProfile(uic).then((profileData) => {
			if(profileData) {
				this.profileData = JSON.parse(profileData); 
				
				for(var k in this.profileData)
				{	//Set value in the form  
					if (this.profileData.hasOwnProperty(k)) {
						this.profileForm.controls[k].setValue(this.profileData[k]);
					}
				}
				
			}
		});
	}

	save(){		
		// if(this.uic !== undefined) {
			// Edit profile
			this.dataService.getUIC().then((uic) => {
				this.uic = uic;

				//Save in local storage
				this.dataService.saveProfile(this.uic, this.profileForm.value);
				console.log(this.profileForm.value);
				this.dataService.setUIC(this.uic);
				this.profileForm.controls['UIC'].setValue(this.uic);
			});
		// }
	}	
}
