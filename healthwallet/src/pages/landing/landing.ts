import { Component } from '@angular/core';
import { ModalController, NavController, NavParams, MenuController} from 'ionic-angular';
import { Profile } from '../profile/profile';
import { Insurance } from '../insurance/insurance';
//import { Registration } from '../registration/registration';
import { AccessManager } from '../accessmanager/accessmanager';
import { Data } from '../../providers/data';
import { File, Transfer } from 'ionic-native';

@Component({
  selector: 'page-landing',
  templateUrl: 'landing.html'
})
export class Landing {
	
	uic: string;
	philhealth: string; 
	insuranceList = [];
	storageDirectory: string;
	qr_img: string;
	
	constructor(public navCtrl: NavController, public navParams: NavParams, public menu: MenuController, public modalCtrl: ModalController, public dataService: Data) {
		this.menu = menu;
		this.menu.enable(true, "appMenu");
		console.log("landing constructor");
		//Set UIC and Philhealth
		// this.setUICandPhilhealth('');
		
		//Set Insurance list
		// this.setInsuranceList();
		
		
	}

	ionViewWillEnter() {
		//Set UIC and Philhealth
		this.setUICandPhilhealth(this.navParams.get('uic'));
		
		//If UIC is not set, call the register page
		/*if(this.uic === undefined || this.uic == '') {
			console.log('uic not set');
			this.navCtrl.push(Registration);
		}*/
		
		//Set Insurance list
		this.setInsuranceList();
	}
	
	setUICandPhilhealth(uic) {
		//Set UIC
		if (uic !== undefined && uic != '') {
			this.uic = uic;
			this.dataService.setUIC(this.uic);

			this.generateQR(this.uic);
			this.getPhilhealth(this.uic);
		}
		else {
			this.dataService.getUIC().then((uic) => {
				if(uic){
					this.uic = uic;
					
					//Generate QR
					this.generateQR(this.uic);
					this.getPhilhealth(this.uic);
				}
			});
		}
		
		
	}
	
	getPhilhealth(uic) {
		//Set Philhealth 
		this.dataService.getProfile(uic).then((profile) => 
		{
			if(profile) {
				this.philhealth = JSON.parse(profile)['Philhealth'];
			}
		});
	}

	setInsuranceList(){
		//Update list of insurance
		if(this.uic !== undefined) {	
			this.dataService.getInsuranceList(this.uic).then((insuranceList) => {
				if(insuranceList){
					this.insuranceList = JSON.parse(insuranceList);
				}
			});
		}
	}
	
	generateQR(uic){
		//We'll temporarily use a webservice until this functionality is available in IONIC 2 
		this.qr_img = "https://zxing.org/w/chart?cht=qr&chs=230x230&chld=L&choe=UTF-8&chl=" + uic;
	}
	  
	viewProfile(){
		this.navCtrl.push(Profile,  {'uic': this.uic});
		
		//Set Philhealth and UIC after profile exits
		// this.setUICandPhilhealth('');
	}

	addInsurance(){
		let addModal = this.modalCtrl.create(Insurance);
		
		addModal.onDidDismiss((insurance_item) => {
			if(insurance_item){	
				this.insuranceList.push(insurance_item);
			}
		});
		
		addModal.present();
	}
	
	viewInsurance(insurance){
		this.navCtrl.push(Insurance, {'uic': this.uic, 'insurance_item': insurance});
	}

	
}
