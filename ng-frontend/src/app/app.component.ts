import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
})
export class AppComponent implements OnInit {
  title = 'ng-frontend';

  constructor(private http: HttpClient) {}

  ngOnInit() {
    // Make the http request:
    this.http.get(`http://sslapi.local/api/items`).subscribe((data) => {
      console.log(data);
    });
  }
}
