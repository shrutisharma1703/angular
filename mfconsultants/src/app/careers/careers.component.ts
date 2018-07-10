import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-careers',
  templateUrl: './careers.component.html',
  styleUrls: ['./careers.component.scss']
})
export class CareersComponent implements OnInit {
  COMING_SOON_LOGO = require("../../assets/co.jpg");
  
  constructor() { }
 
  ngOnInit() {
  }

}
