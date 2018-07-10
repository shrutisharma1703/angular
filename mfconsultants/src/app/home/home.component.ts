import { Component, OnInit } from '@angular/core';

@Component({
  selector: "[titlecase-pipe],[lowerupper-pipe],[date-pipe],[currency-pipe]",
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {

  constructor() { }
  tagline : string;
  courses : string;
  tagline = "top colleges admission consultants";
  courses = "mba, ms in it, ms in finance";
  //get date
  today: number = Date.now();
  CourseDeatil = [
	{ name: 'mba' , price: '500' } ,
	{ name: 'ms in it' , price: '600' } ,
	{ name: 'ms in finance' , price: '700' }
	]
  
  ngOnInit() {
  }

}
