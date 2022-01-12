<!DOCTYPE html>
<html>
<head>
    <title>Title From OnlineWebTutorBlog</title>
    <link rel="stylesheet" href="{{ public_path('css/pdf-file.css') }}">
</head>
<body>
    
    <div class="page">
        <!-- Define header and footer blocks before your content -->
        <header>
            <div class="left-div">
                <img src="{{ public_path('images/pup-logo-Polytechnic_University_of_the_Philippines.png') }}" style="width: 100px; height: 100px">
                <img src="{{ public_path('/storage/'. $organization->logo->file) }}" style="width: 100px; height: 100px">
                
            </div>
            <div class="right-div">
                    <small>Republic of the Philippines   </small> 
                    <p> POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</p>  
                    <p>Office of the Vice President for Branches and Satellite Campuses</p>
                    <p>TAGUIG BRANCH</p>
                    <p>Office of Student Services</p>
                    <p>COMPUTER SOCIETY</p>
            </div>
        </header>
        <hr>
        <h4 class="text-center">
            GENERAL PLAN OF ACTIVITIES  <br>
            FIRST SEMESTER <br>
            ACADEMIC YEAR 2021-2022

        </h4>
        <div>        
            @if (isset($upcoming_events))
                <table class="main-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Date</th>
                            <th>Name/Title of Activity</th>
                            <th>Objectives</th>
                            <th>Participant(s)/ Beneficiary(ies)
                                (indicate the number)</th>
                            <th>Head Organization</th>
                            <th>In partnership w/ (if there is any)</th>
                            <th>Venue & time</th>
                            <th>Type of Activity</th>
                            <th>Projected Budget</th>
                            <th>Fund Sourcing
                                Org. Fund / Sponsor
                                (Pls. indicate)
                                </th>
                            <th>Remarks</th>               
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @php
                            $data = 0
                        @endphp
                        @if ($upcoming_events->isNotEmpty())
                            @foreach ($upcoming_events as $upcoming_event)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date_format(date_create($upcoming_event->date), 'F d, Y') }}</td>
                                    <td>{{ $upcoming_event->title }}</td>
                                    <td>{{ $upcoming_event->objectives }}</td>
                                    <td>{{ $upcoming_event->participants }}</td>
                                    <td>{{ $upcoming_event->head_organization }}</td>
                                    <td>{{ $upcoming_event->partnerships }}</td>
                                    <td>{{ $upcoming_event->venue }} / {{ date_format(date_create($upcoming_event->time), 'H : i a')}}</td>
                                    <td>{{ $upcoming_event->activity_type }}</td>
                                    <td>{{ $upcoming_event->projected_budget }}</td>
                                    <td>{{ $upcoming_event->fund_source }} / {{ $upcoming_event->sponsor }}</td>
                                    <td></td>
                                    
                                </tr>
                               @php
                                   $data++;
                               @endphp
                                @if ($data == 1)

                                </tbody>
                                </table>
                                <footer>
                                    <p class="footer-text">Gen. Santos Ave. Lower Bicutan, Taguig City 1772; (Direct Line) 837-5858 to 60; (Telefax) 837-5859;</p> 
                                         
                                     <p>website: www.pup.edu.ph     e-mail: taguig@pup.edu.ph </p>
                         
                                         <p>“THE COUNTRY’S 1st POLYTECHNIC UNIVERSITY”</p>

                                </footer>
                                @if (!$loop->last)
                                <div class="page-break"></div>
                                <header>
                                    <div class="left-div">
                                        <img src="{{ public_path('images/pup-logo-Polytechnic_University_of_the_Philippines.png') }}" style="width: 100px; height: 100px">
                                        <img src="{{ public_path('/storage/'. $organization->logo->file) }}" style="width: 100px; height: 100px">
                                        
                                    </div>
                                    <div class="right-div">
                                            <small>Republic of the Philippines   </small> 
                                            <p> POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</p>  
                                            <p>Office of the Vice President for Branches and Satellite Campuses</p>
                                            <p>TAGUIG BRANCH</p>
                                            <p>Office of Student Services</p>
                                            <p>COMPUTER SOCIETY</p>
                                    </div>
                                </header>
                                <hr>
                                <table class="main-table">
                                     <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Date</th>
                                            <th>Name/Title of Activity</th>
                                            <th>Objectives</th>
                                            <th>Participant(s)/ Beneficiary(ies)
                                                (indicate the number)</th>
                                            <th>Head Organization</th>
                                            <th>In partnership w/ (if there is any)</th>
                                            <th>Venue & time</th>
                                            <th>Type of Activity</th>
                                            <th>Projected Budget</th>
                                            <th>Fund Sourcing
                                                Org. Fund / Sponsor
                                                (Pls. indicate)
                                                </th>
                                            <th>Remarks</th>               
                                        </tr>
                                    </thead>
                                <tbody class="text-center">
                                @endif

                                              
                                    
                                    @php
                                        $data=0;
                                    @endphp
                                @endif

                            @endforeach
                           
                        @else
                        <tr><td colspan="7">No results found!</td></tr>
                        @endif
                    </tbody>
                </table>
                
            @endif
        </div>
        
        <footer>
          
            <p class="footer-text">Gen. Santos Ave. Lower Bicutan, Taguig City 1772; (Direct Line) 837-5858 to 60; (Telefax) 837-5859;</p> 
                 
             <p>website: www.pup.edu.ph     e-mail: taguig@pup.edu.ph </p>
 
                 <p>“THE COUNTRY’S 1st POLYTECHNIC UNIVERSITY”</p>
 
            
         </footer>
         
    </div>
    <div class="page-break"></div>
    <div class="page">
        <header>
            <div class="left-div">
                <img src="{{ public_path('images/pup-logo-Polytechnic_University_of_the_Philippines.png') }}" style="width: 100px; height: 100px">
                <img src="{{ public_path('/storage/'. $organization->logo->file) }}" style="width: 100px; height: 100px">
                
            </div>
            <div class="right-div">
                    <small>Republic of the Philippines   </small> 
                    <p> POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</p>  
                    <p>Office of the Vice President for Branches and Satellite Campuses</p>
                    <p>TAGUIG BRANCH</p>
                    <p>Office of Student Services</p>
                    <p>COMPUTER SOCIETY</p>
            </div>
        </header>
        <hr>
        <h4 class="text-center">
           BREAKDOWN OF FEES (Membership Fee)
        </h4>
        <table class="breakdown-table"> 
            <tr>
                <th>REQUIRED</th>
                <TH>AMOUNT</TH>
            </tr> 
            <tr>
                <td>CS Membership Fee</td>
                <td>P 0.00</td>
            </tr>
            <tr>   
                <td>Total Collection</td>
                <td>P 0.00</td>               
            </tr>
        </table>
        <div class="upper-signatures">
            <div class="prepared">
                <p>Prepared by:</p>
                <small>name</small><br>
                <small>President, Computer Society	</small>
            </div>
           <div class="approved">
                <p>Approved by:</p>
                <small>name</small><br>
                <small>Head of Student Services</small>
           </div>
           
        </div>
        <div class="lower-signatures">
            <p>Noted by:</p>
            <div class="adviser">
                <small>name</small><br>
                <small>Adviser</small>
            </div>
            <div class="director">
                <small>name</small><br>
                <small>Director</small>
            </div>
            
        </div>
       
       
        <footer>
          
            <p class="footer-text">Gen. Santos Ave. Lower Bicutan, Taguig City 1772; (Direct Line) 837-5858 to 60; (Telefax) 837-5859;</p> 
                 
             <p>website: www.pup.edu.ph     e-mail: taguig@pup.edu.ph </p>
 
                 <p>“THE COUNTRY’S 1st POLYTECHNIC UNIVERSITY”</p>
 
            
         </footer>
    </div>
</body>
</html>