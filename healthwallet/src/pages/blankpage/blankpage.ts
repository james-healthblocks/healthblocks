import { Component } from '@angular/core';
import { NavController, NavParams, MenuController } from 'ionic-angular';
import { Data } from '../../providers/data';

import { Registration } from '../registration/registration';
import { Landing } from '../landing/landing';
/*
  Generated class for the Blankpage page.

  See http://ionicframework.com/docs/v2/components/#navigation for more info on
  Ionic pages and navigation.
*/
@Component({
  selector: 'page-blankpage',
  templateUrl: 'blankpage.html'
})
export class Blankpage {

  constructor(public navCtrl: NavController, public navParams: NavParams, public menu: MenuController, public dataService: Data) {
    this.menu = menu;
    this.menu.enable(false, "appMenu");

    this.dataService.getUIC().then((uic) => {
      if(uic) {
        this.navCtrl.setRoot(Landing);
      }
      else {
        this.navCtrl.setRoot(Registration);
      }
    });
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad BlankpagePage');
  }

}
