import { Injectable } from '@angular/core';
import { Storage } from '@ionic/storage';
import 'rxjs/add/operator/map';

/*
  Generated class for the Data provider.

  See https://angular.io/docs/ts/latest/guide/dependency-injection.html
  for more info on providers and Angular 2 DI.
*/
@Injectable()
export class Data {

  private storage;
  
  constructor(){
	this.storage = new Storage();
  }
  
  getUIC() {
	return this.storage.get('uic');
  }
  
  setUIC(uic) {
	//This should be called during login 
	this.storage.set('uic', uic);
  }

  unsetUIC(uic)	{
	//This should be called during logout  
	this.storage.remove('uic');
  }
  
  getWalletAddress() {
  	return this.storage.get('wallet_address');
  }
  
  setWalletAddress(address) {
  	this.storage.set('wallet_address', address);
  }
 
  getProfile(uic) {
	return this.storage.get('profile_' + uic);  
  }
  
  getInsuranceList(uic)	{
	return this.storage.get('insurance_' + uic);
  }
  
  saveProfile(uic, data){
	this.storage.set('profile_' + uic, JSON.stringify(data));
  }
  
  saveInsurance(uic, list) {	  
	this.storage.set('insurance_' + uic, JSON.stringify(list));
  }
  
  clear() {
	this.storage.clear();
  }

}
