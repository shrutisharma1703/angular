import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})

export class HeaderComponent implements OnInit {
  
 title = 'MF Consultancy';
  
//  @Input() imgPath:string = "app/assets/";
 // imgFileName:string = "comingsoon.jpeg";
  constructor() { }
  Topmenu = [
	{ name: 'Home' , link: '' } ,
	{ name: 'About' , link: 'about' } ,
	{ name: 'Careers' , link: 'careers' }, 
	{ name: 'Contact Us' , link: 'contactus' }, 
	]
  ngOnInit() {
  }

}
