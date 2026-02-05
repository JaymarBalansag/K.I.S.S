<template>
  <div class="main-container min-vh-100 d-flex flex-column">
    <main class="flex-grow-1 py-5 mt-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-9">

            <div class="text-start mb-4">
              <a @click.prevent="$router.back()" class="text-white text-decoration-none opacity-75 hover-opacity-100" style="cursor: pointer;">
                <span class="fs-5">←</span> Back
              </a>
            </div>
            
            <div class="card glass-card shadow-lg">
              <div class="card-header p-4 border-0">
                <h2 class="text-white fw-bold mb-1">
                  Marriage License Application
                </h2>
                <p class="text-white opacity-75 mb-0">
                  Step {{ step }} of {{ totalSteps }}
                </p>
              </div>

              <div class="card-body p-4 p-md-5">

                <!-- STEP 1 – GROOM NAME -->
                <div v-if="step === 1">
                  <h4 class="section-title">Groom – Name</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="form-label">First Name</label>
                      <input class="form-control" v-model="form.groom.firstName">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Middle Name</label>
                      <input class="form-control" v-model="form.groom.middleName">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Last Name</label>
                      <input class="form-control" v-model="form.groom.lastName">
                    </div>
                  </div>
                </div>

                <!-- STEP 2 – GROOM BIRTH DATE -->
                <div v-if="step === 2">
                  <h4 class="section-title">Groom – Birth Date Details</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="form-label">Day</label>
                      <input type="number" class="form-control" v-model="form.groom.day">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Month</label>
                      <input type="number" class="form-control" v-model="form.groom.month">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Year</label>
                      <input type="number" class="form-control" v-model="form.groom.year">
                    </div>
                  </div>
                </div>

                <!-- STEP 3 – GROOM PLACE OF BIRTH -->
                <div v-if="step === 3">
                  <h4 class="section-title">Groom – Place of Birth</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="form-label">City / Municipality</label>
                      <input class="form-control" v-model="form.groom.cityMunicipality">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Province</label>
                      <input class="form-control" v-model="form.groom.province">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Country</label>
                      <input class="form-control" v-model="form.groom.country">
                    </div>
                  </div>
                </div>

                <!-- STEP 4 – GROOM INFO -->
                <div v-if="step === 4">
                  <h4 class="section-title">Groom – Personal Info</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="form-label">Age</label>
                      <input class="form-control bg-light" v-model="form.groom.age" readonly placeholder="Auto-calculated">
                      <small v-if="ageError" class="text-danger fw-bold">{{ ageError }}</small>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Sex</label>
                      <select class="form-control" v-model="form.groom.sex">
                        <option value="" disabled>Select Sex</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                      </select>
                    </div>

                    <div class="col-md-4">
                      <label class="form-label">Citizenship</label>
                      <select class="form-control" v-model="form.groom.citizenship">
                        <option value="" disabled>Select Citizenship</option>
                        <option value="Filipino">Filipino</option>
                        <option value="Dual Citizenship">Dual Citizenship</option>
                        <option value="Foreign National">Foreign National</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- STEP 5 – GROOM RESIDENCE -->
                <div v-if="step === 5">
                  <h4 class="section-title">Groom – Residence</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label">House No., Street, City/Municipality, Province, Country</label>
                      <input class="form-control" v-model="form.groom.residence">
                      <small class=" text-primary">Tips: Enter the full address including house number, street name, city/municipality, province, and country.</small>
                    </div>
                  </div>
                </div>

                <!-- STEP 6 – GROOM RELIGION -->
                <div v-if="step === 6">
                  <h4 class="section-title">Groom – Religion / Religion Sect</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label">Religion / Religion Sect</label>
                      <input class="form-control" v-model="form.groom.religion">
                    </div>
                  </div>
                </div>
                
                <!-- STEP 7 – GROOM CIVIL STATUS -->
                <div v-if="step === 7">
                  <h4 class="section-title">Groom – Civil Status</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label">Civil Status</label>
                      <select class="form-control" v-model="form.groom.civilStatus">
                        <option value="Single">Single</option>
                        <option value="Widowed">Widowed</option>
                        <option value="Annulled">Annulled</option>
                        <option value="Divorced">Divorced (for Foreigners/Legal cases)</option>
                      </select>
                    </div>
                    <div v-if="isPreviouslyMarried" class="col-md-12">
                      <label class="form-label">IF PREVIOUSLY MARRIED: How was it dissolved?</label>
                      <textarea class="form-control" cols="2" row="2" v-model="form.groom.previousMarriageDissolve"></textarea>
                    </div>
                    <div v-if="isPreviouslyMarried" class="col-md-12">
                      <h4 class="section-title">Place where dissolved</h4>
                      <div class="row g-3">
                        <div class="col-md-4">
                          <label class="form-label">City / Municipality</label>
                          <input class="form-control" v-model="form.groom.dissolvedCityMunicipality">
                        </div>
                        <div class="col-md-4">
                          <label class="form-label">Province</label>
                          <input class="form-control" v-model="form.groom.dissolvedProvince">
                        </div>
                        <div class="col-md-4">
                          <label class="form-label">Country</label>
                          <input class="form-control" v-model="form.groom.dissolvedCountry">
                        </div>
                      </div>
                    </div>
                    <div v-if="isPreviouslyMarried" class="col-md-12">
                      <h4 class="section-title">Date when dissolved</h4>
                      <div class="row g-3">
                        <div class="col-md-4">
                          <label class="form-label">Day</label>
                          <input type="number" class="form-control" v-model="form.groom.dissolvedDay">
                        </div>
                        <div class="col-md-4">
                          <label class="form-label">Month</label>
                          <input type="number" class="form-control" v-model="form.groom.dissolvedMonth">
                        </div>
                        <div class="col-md-4">
                          <label class="form-label">Year</label>
                          <input type="number" class="form-control" v-model="form.groom.dissolvedYear">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- STEP 8 – GROOM DEGREE -->
                <div v-if="step === 8">
                  <h4 class="section-title">Groom - Degree of relationship contracting parties</h4>
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label">Degree</label>
                      <input class="form-control" v-model="form.groom.degree">
                      <small class=" text-primary">Tips: you can leave this blank if not applicable.</small>
                    </div>
                  </div>
                </div>

                <!-- STEP 9 – GROOM FATHER'S INFO -->
                <div v-if="step === 9">
                  <h4 class="section-title">Groom – Father's Info</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="form-label">First Name</label>
                      <input class="form-control" v-model="form.groom.fatherFirstName">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Middle Name</label>
                      <input class="form-control" v-model="form.groom.fatherMiddleName">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Last Name</label>
                      <input class="form-control" v-model="form.groom.fatherLastName">
                    </div>
                    <div class="col">
                      <label class="form-label">Citizenship</label>
                      <select class="form-control" v-model="form.groom.fatherCitizenship">
                        <option value="" disabled>Select Citizenship</option>
                        <option value="Filipino">Filipino</option>
                        <option value="Dual Citizenship">Dual Citizenship</option>
                        <option value="Foreign National">Foreign National</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- STEP 10 – GROOM FATHER'S RESIDENCE -->
                <div v-if="step === 10">
                  <h4 class="section-title">Groom – Father's Residence</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label">House No., Street, City/Municipality, Province, Country</label>
                      <input class="form-control" v-model="form.groom.fatherResidence">
                      <small class=" text-primary">Tips: Enter the full address including house number, street name, city/municipality, province, and country.</small>
                    </div>
                  </div>
                </div>

                <!-- STEP 11 – GROOM MOTHER'S MAIDEN INFO -->
                <div v-if="step === 11">
                  <h4 class="section-title">Groom – Mother's Maiden Info</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="form-label">First Name</label>
                      <input class="form-control" v-model="form.groom.motherMaidenFirstName">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Middle Name</label>
                      <input class="form-control" v-model="form.groom.motherMaidenMiddleName">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Last Name</label>
                      <input class="form-control" v-model="form.groom.motherMaidenLastName">
                    </div>
                    <div class="col">
                      <label class="form-label">Citizenship</label>
                      <select class="form-control" v-model="form.groom.motherMaidenCitizenship">
                        <option value="" disabled>Select Citizenship</option>
                        <option value="Filipino">Filipino</option>
                        <option value="Dual Citizenship">Dual Citizenship</option>
                        <option value="Foreign National">Foreign National</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- STEP 12 – GROOM MOTHER'S MAIDEN RESIDENCE -->
                <div v-if="step === 12">
                  <h4 class="section-title">Groom – Mother's Maiden Residence</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label">House No., Street, City/Municipality, Province, Country</label>
                      <input class="form-control" v-model="form.groom.motherMaidenResidence">
                      <small class=" text-primary">Tips: Enter the full address including house number, street name, city/municipality, province, and country.</small>
                    </div>
                  </div>
                </div>

                <!-- STEP 13 – GROOM REVIEW -->
                <div v-if="step === 13">
                  <h4 class="section-title mb-4">Review Groom's Full Information</h4>
                  <div class="review-scroll-area glass-inner">
                    <table class="table table-sm text-white review-table mb-0">
                      <thead>
                        <tr class="table-header-row"><th colspan="2">Personal Details</th></tr>
                      </thead>
                      <tbody>
                        <tr><td>Full Name</td><td class="text-info fw-bold">{{ form.groom.firstName }} {{ form.groom.middleName }} {{ form.groom.lastName }}</td></tr>
                        <tr><td>Birth Details</td><td>{{ form.groom.month }}/{{ form.groom.day }}/{{ form.groom.year }} (Age: {{ form.groom.age }})</td></tr>
                        <tr><td>Birth Place</td><td>{{ form.groom.cityMunicipality }}, {{ form.groom.province }}</td></tr>
                        <tr><td>Identity</td><td>{{ form.groom.sex }} | {{ form.groom.citizenship }} | {{ form.groom.religion }}</td></tr>
                        <tr><td>Civil Status</td><td><span class="badge bg-primary glow-badge">{{ form.groom.civilStatus }}</span></td></tr>
                        <tr v-if="form.groom.civilStatus !== 'Single'">
                          <td>Dissolution</td>
                          <td class="small text-warning">{{ form.groom.previousMarriageDissolve }}</td>
                        </tr>
                        <tr><td>Residence</td><td class="small opacity-75">{{ form.groom.residence }}</td></tr>
                      </tbody>
                      <thead>
                        <tr class="table-header-row"><th colspan="2">Family Details</th></tr>
                      </thead>
                      <tbody>
                        <tr><td>Father</td><td>{{ form.groom.fatherFirstName }} {{ form.groom.fatherLastName }} ({{ form.groom.fatherCitizenship }})</td></tr>
                        <tr><td>Mother</td><td>{{ form.groom.motherMaidenFirstName }} {{ form.groom.motherMaidenLastName }} ({{ form.groom.motherMaidenCitizenship }})</td></tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- STEP 14 – BRIDE NAME -->
                <div v-if="step === 14">
                  <h4 class="section-title">Bride – Name</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="form-label">First Name</label>
                      <input class="form-control" v-model="form.bride.firstName">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Middle Name</label>
                      <input class="form-control" v-model="form.bride.middleName">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Last Name</label>
                      <input class="form-control" v-model="form.bride.lastName">
                    </div>
                  </div>
                </div>

                <!-- STEP 15 – BRIDE BIRTH DATE -->
                <div v-if="step === 15">
                  <h4 class="section-title">Bride – Birth Date Details</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="form-label">Day</label>
                      <input type="number" class="form-control" v-model="form.bride.day">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Month</label>
                      <input type="number" class="form-control" v-model="form.bride.month">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Year</label>
                      <input type="number" class="form-control" v-model="form.bride.year">
                    </div>
                  </div>
                </div>

                <!-- STEP 16 – BRIDE PLACE OF BIRTH -->
                <div v-if="step === 16">
                  <h4 class="section-title">Bride – Place of Birth</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="form-label">City / Municipality</label>
                      <input class="form-control" v-model="form.bride.cityMunicipality">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Province</label>
                      <input class="form-control" v-model="form.bride.province">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Country</label>
                      <input class="form-control" v-model="form.bride.country">
                    </div>
                  </div>
                </div>

                <!-- STEP 17 – BRIDE INFO -->
                <div v-if="step === 17">
                  <h4 class="section-title">Bride – Personal Info</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="form-label">Age</label>
                      <input class="form-control" v-model="form.bride.age">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Sex</label>
                      <select class="form-control" v-model="form.bride.sex">
                        <option value="" disabled>Select Sex</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                      </select>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Citizenship</label>
                      <select class="form-control" v-model="form.bride.citizenship">
                        <option value="" disabled>Select Citizenship</option>
                        <option value="Filipino">Filipino</option>
                        <option value="Dual Citizenship">Dual Citizenship</option>
                        <option value="Foreign National">Foreign National</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- STEP 18 – BRIDE RESIDENCE -->
                <div v-if="step === 18">
                  <h4 class="section-title">Bride – Residence</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label">House No., Street, City/Municipality, Province, Country</label>
                      <input class="form-control" v-model="form.bride.residence">
                      <small class=" text-primary">Tips: Enter the full address including house number, street name, city/municipality, province, and country.</small>
                    </div>
                  </div>
                </div>

                <!-- STEP 19 – BRIDE RELIGION -->
                <div v-if="step === 19">
                  <h4 class="section-title">Bride – Religion / Religion Sect</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label">Religion / Religion Sect</label>
                      <input class="form-control" v-model="form.bride.religion">
                    </div>
                  </div>
                </div>
                
                <!-- STEP 20 – BRIDE CIVIL STATUS -->
                <div v-if="step === 20">
                  <h4 class="section-title">Bride – Civil Status</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label">Civil Status</label>
                      <select class="form-control" v-model="form.bride.civilStatus">
                        <option value="Single">Single</option>
                        <option value="Widowed">Widowed</option>
                        <option value="Annulled">Annulled</option>
                        <option value="Divorced">Divorced (for Foreigners/Legal cases)</option>
                      </select>
                    </div>
                    <div v-if="isPreviouslyMarried" class="col-md-12">
                      <label class="form-label">IF PREVIOUSLY MARRIED: How was it dissolved?</label>
                      <textarea class="form-control" cols="2" row="2" v-model="form.bride.previousMarriageDissolve"></textarea>
                    </div>
                    <div v-if="isPreviouslyMarried" class="col-md-12">
                      <h4 class="section-title">Place where dissolved</h4>
                      <div class="row g-3">
                        <div class="col-md-4">
                          <label class="form-label">City / Municipality</label>
                          <input class="form-control" v-model="form.bride.dissolvedCityMunicipality">
                        </div>
                        <div class="col-md-4">
                          <label class="form-label">Province</label>
                          <input class="form-control" v-model="form.bride.dissolvedProvince">
                        </div>
                        <div class="col-md-4">
                          <label class="form-label">Country</label>
                          <input class="form-control" v-model="form.bride.dissolvedCountry">
                        </div>
                      </div>
                    </div>
                    <div v-if="isPreviouslyMarried" class="col-md-12">
                      <h4 class="section-title">Date when dissolved</h4>
                      <div class="row g-3">
                        <div class="col-md-4">
                          <label class="form-label">Day</label>
                          <input type="number" class="form-control" v-model="form.bride.dissolvedDay">
                        </div>
                        <div class="col-md-4">
                          <label class="form-label">Month</label>
                          <input type="number" class="form-control" v-model="form.bride.dissolvedMonth">
                        </div>
                        <div class="col-md-4">
                          <label class="form-label">Year</label>
                          <input type="number" class="form-control" v-model="form.bride.dissolvedYear">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- STEP 21 – BRIDE DEGREE -->
                <div v-if="step === 21">
                  <h4 class="section-title">Brides - Degree of relationship contracting parties</h4>
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label">Degree</label>
                      <input class="form-control" v-model="form.bride.degree">
                      <small class=" text-primary">Tips: you can leave this blank if not applicable.</small>
                    </div>
                  </div>
                </div>

                <!-- STEP 22 – BRIDE FATHER'S INFO -->
                <div v-if="step === 22">
                  <h4 class="section-title">Bride – Father's Info</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="form-label">First Name</label>
                      <input class="form-control" v-model="form.bride.fatherFirstName">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Middle Name</label>
                      <input class="form-control" v-model="form.bride.fatherMiddleName">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Last Name</label>
                      <input class="form-control" v-model="form.bride.fatherLastName">
                    </div>
                    <div class="col">
                      <label class="form-label">Citizenship</label>
                      <select class="form-control" v-model="form.bride.fatherCitizenship">
                        <option value="" disabled>Select Citizenship</option>
                        <option value="Filipino">Filipino</option>
                        <option value="Dual Citizenship">Dual Citizenship</option>
                        <option value="Foreign National">Foreign National</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- STEP 23 – BRIDE FATHER'S RESIDENCE -->
                <div v-if="step === 23">
                  <h4 class="section-title">Bride – Father's Residence</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label">House No., Street, City/Municipality, Province, Country</label>
                      <input class="form-control" v-model="form.bride.fatherResidence">
                      <small class=" text-primary">Tips: Enter the full address including house number, street name, city/municipality, province, and country.</small>
                    </div>
                  </div>
                </div>

                <!-- STEP 24 – BRIDE MOTHER'S MAIDEN INFO -->
                <div v-if="step === 24">
                  <h4 class="section-title">Bride – Mother's Maiden Info</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="form-label">First Name</label>
                      <input class="form-control" v-model="form.bride.motherMaidenFirstName">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Middle Name</label>
                      <input class="form-control" v-model="form.bride.motherMaidenMiddleName">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Last Name</label>
                      <input class="form-control" v-model="form.bride.motherMaidenLastName">
                    </div>
                    <div class="col">
                      <label class="form-label">Citizenship</label>
                      <select class="form-control" v-model="form.bride.motherMaidenCitizenship">
                        <option value="" disabled>Select Citizenship</option>
                        <option value="Filipino">Filipino</option>
                        <option value="Dual Citizenship">Dual Citizenship</option>
                        <option value="Foreign National">Foreign National</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- STEP 25 – BRIDE MOTHER'S MAIDEN RESIDENCE -->
                <div v-if="step === 25">
                  <h4 class="section-title">Bride – Mother's Maiden Residence</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label">House No., Street, City/Municipality, Province, Country</label>
                      <input class="form-control" v-model="form.bride.motherMaidenResidence">
                      <small class=" text-primary">Tips: Enter the full address including house number, street name, city/municipality, province, and country.</small>
                    </div>
                  </div>
                </div>

                <!-- STEP 26 – BRIDE REVIEW -->
                <div v-if="step === 26">
                  <h4 class="section-title mb-4">Review Bride's Full Information</h4>
                  <div class="review-scroll-area glass-inner">
                    <table class="table table-sm text-white review-table mb-0">
                      <thead>
                        <tr class="table-header-row"><th colspan="2">Personal Details</th></tr>
                      </thead>
                      <tbody>
                        <tr><td>Full Name</td><td class="text-info fw-bold">{{ form.bride.firstName }} {{ form.bride.middleName }} {{ form.bride.lastName }}</td></tr>
                        <tr><td>Birth Details</td><td>{{ form.bride.month }}/{{ form.bride.day }}/{{ form.bride.year }} (Age: {{ form.bride.age }})</td></tr>
                        <tr><td>Birth Place</td><td>{{ form.bride.cityMunicipality }}, {{ form.bride.province }}</td></tr>
                        <tr><td>Identity</td><td>{{ form.bride.sex }} | {{ form.bride.citizenship }} | {{ form.bride.religion }}</td></tr>
                        <tr><td>Civil Status</td><td><span class="badge bg-danger glow-badge">{{ form.bride.civilStatus }}</span></td></tr>
                        <tr><td>Residence</td><td class="small opacity-75">{{ form.bride.residence }}</td></tr>
                      </tbody>
                      <thead>
                        <tr class="table-header-row"><th colspan="2">Family Details</th></tr>
                      </thead>
                      <tbody>
                        <tr><td>Father</td><td>{{ form.bride.fatherFirstName }} {{ form.bride.fatherLastName }}</td></tr>
                        <tr><td>Mother</td><td>{{ form.bride.motherMaidenFirstName }} {{ form.bride.motherMaidenLastName }}</td></tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- STEP 27 – REVIEW -->
                <div v-if="step === 27">
                  <h4 class="section-title mb-4 text-center border-0">Final Verification</h4>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="glass-inner p-3 h-100 border-primary-subtle border-start border-4">
                        <h6 class="text-white fw-bold text-uppercase small mb-3">Groom Details</h6>
                        <div class="final-review-list text-white">
                          <p><strong>Name:</strong> {{ form.groom.lastName }}, {{ form.groom.firstName }}</p>
                          <p><strong>Age/Status:</strong> {{ form.groom.age }} / {{ form.groom.civilStatus }}</p>
                          <p><strong>Religion:</strong> {{ form.groom.religion }}</p>
                          <p class="small opacity-75"><strong>Address:</strong> {{ form.groom.residence }}</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="glass-inner p-3 h-100 border-info-subtle border-start border-4">
                        <h6 class="text-white fw-bold text-uppercase small mb-3">Bride Details</h6>
                        <div class="final-review-list text-white">
                          <p><strong>Name:</strong> {{ form.bride.lastName }}, {{ form.bride.firstName }}</p>
                          <p><strong>Age/Status:</strong> {{ form.bride.age }} / {{ form.bride.civilStatus }}</p>
                          <p><strong>Religion:</strong> {{ form.bride.religion }}</p>
                          <p class="small opacity-75"><strong>Address:</strong> {{ form.bride.residence }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- STEP 28 – Marriage Required Documents -->
                <div v-if="step === 28" class="glass-panel">
                  <h4 class="section-title text-white mb-4">Required Documents</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>  
                  <div class="row g-4">
                    <!-- For Groom -->
                    <div class="col-md-6 border-end border-white-10">
                      <h5 class="text-white small fw-bold text-uppercase mb-3"><i class="bi bi-gender-male"></i> Groom's Documents</h5>
                      
                      <div class="mb-4">
                        <label class="form-label fw-semibold small text-uppercase text-white-50">CENOMAR <span class="text-info">*</span></label>
                        <div class="glass-upload-container position-relative">
                          <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'groom', 'cenomar')" accept="image/*,.pdf" />
                          <div class="glass-placeholder border-glass rounded-3 p-3 text-center">
                            <div v-if="!previews.groom.cenomar">
                              <i class="bi bi-cloud-arrow-up fs-3 text-white"></i>
                              <p class="mb-0 smallest text-white">Upload Groom CENOMAR</p>
                            </div>
                            <div v-else class="animate-fade-in">
                              <img v-if="isImage('groom', 'cenomar')" :src="previews.groom.cenomar" class="img-fluid rounded-2 glass-preview-img-sm" />
                              <div v-else class="py-2"><i class="bi bi-file-earmark-pdf fs-2 text-info"></i></div>
                              <div class="glass-badge-sm">Selected</div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="mb-4">
                        <label class="form-label fw-semibold small text-uppercase text-white-50">PSA Birth Certificate <span class="text-info">*</span></label>
                        <div class="glass-upload-container position-relative">
                          <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'groom', 'psa')" accept="image/*,.pdf" />
                          <div class="glass-placeholder border-glass rounded-3 p-3 text-center">
                            <div v-if="!previews.groom.psa">
                              <i class="bi bi-cloud-arrow-up fs-3 text-white"></i>
                              <p class="mb-0 smallest text-white">Upload Groom PSA</p>
                            </div>
                            <div v-else class="animate-fade-in">
                              <img v-if="isImage('groom', 'psa')" :src="previews.groom.psa" class="img-fluid rounded-2 glass-preview-img-sm" />
                              <div v-else class="py-2"><i class="bi bi-file-earmark-pdf fs-2 text-info"></i></div>
                              <div class="glass-badge-sm">Selected</div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="mb-4">
                        <label class="form-label fw-semibold small text-uppercase text-white-50">National ID or any govt-issued ID <span class="text-info">*</span></label>
                        <div class="glass-upload-container position-relative">
                          <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'groom', 'govtIssuedId')" accept="image/*,.pdf" />
                          <div class="glass-placeholder border-glass rounded-3 p-3 text-center">
                            <div v-if="!previews.groom.govtIssuedId">
                              <i class="bi bi-cloud-arrow-up fs-3 text-white"></i>
                              <p class="mb-0 smallest text-white">Upload Groom Government Issued ID</p>
                            </div>
                            <div v-else class="animate-fade-in">
                              <img v-if="isImage('groom', 'govtIssuedId')" :src="previews.groom.govtIssuedId" class="img-fluid rounded-2 glass-preview-img-sm" />
                              <div v-else class="py-2"><i class="bi bi-file-earmark-pdf fs-2 text-info"></i></div>
                              <div class="glass-badge-sm">Selected</div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="mb-4">
                        <label class="form-label fw-semibold small text-uppercase text-white-50">PMOC Certificate <span class="text-info">*</span></label>
                        <div class="glass-upload-container position-relative">
                          <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'groom', 'pmocCertificate')" accept="image/*,.pdf" />
                          <div class="glass-placeholder border-glass rounded-3 p-3 text-center">
                            <div v-if="!previews.groom.pmocCertificate">
                              <i class="bi bi-cloud-arrow-up fs-3 text-white"></i>
                              <p class="mb-0 smallest text-white">Upload Groom PMOC Certificate</p>
                            </div>
                            <div v-else class="animate-fade-in">
                              <img v-if="isImage('groom', 'pmocCertificate')" :src="previews.groom.pmocCertificate" class="img-fluid rounded-2 glass-preview-img-sm" />
                              <div v-else class="py-2"><i class="bi bi-file-earmark-pdf fs-2 text-info"></i></div>
                              <div class="glass-badge-sm">Selected</div>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>

                    <!-- For Bride -->
                    <div class="col-md-6">
                      <h5 class="text-white small fw-bold text-uppercase mb-3"><i class="bi bi-gender-female"></i> Bride's Documents</h5>
                      
                      <div class="mb-4">
                        <label class="form-label fw-semibold small text-uppercase text-white-50">CENOMAR <span class="text-info">*</span></label>
                        <div class="glass-upload-container position-relative">
                          <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'bride', 'cenomar')" accept="image/*,.pdf" />
                          <div class="glass-placeholder border-glass rounded-3 p-3 text-center">
                            <div v-if="!previews.bride.cenomar">
                              <i class="bi bi-cloud-arrow-up fs-3 text-white"></i>
                              <p class="mb-0 smallest text-white">Upload Bride CENOMAR</p>
                            </div>
                            <div v-else class="animate-fade-in">
                              <img v-if="isImage('bride', 'cenomar')" :src="previews.bride.cenomar" class="img-fluid rounded-2 glass-preview-img-sm" />
                              <div v-else class="py-2"><i class="bi bi-file-earmark-pdf fs-2 text-info"></i></div>
                              <div class="glass-badge-sm">Selected</div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="mb-4">
                        <label class="form-label fw-semibold small text-uppercase text-white-50">PSA Birth Certificate <span class="text-info">*</span></label>
                        <div class="glass-upload-container position-relative">
                          <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'bride', 'psa')" accept="image/*,.pdf" />
                          <div class="glass-placeholder border-glass rounded-3 p-3 text-center">
                            <div v-if="!previews.bride.psa">
                              <i class="bi bi-cloud-arrow-up fs-3 text-white"></i>
                              <p class="mb-0 smallest text-white">Upload Bride PSA</p>
                            </div>
                            <div v-else class="animate-fade-in">
                              <img v-if="isImage('bride', 'psa')" :src="previews.bride.psa" class="img-fluid rounded-2 glass-preview-img-sm" />
                              <div v-else class="py-2"><i class="bi bi-file-earmark-pdf fs-2 text-info"></i></div>
                              <div class="glass-badge-sm">Selected</div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="mb-4">
                        <label class="form-label fw-semibold small text-uppercase text-white-50">National ID or any govt-issued ID <span class="text-info">*</span></label>
                        <div class="glass-upload-container position-relative">
                          <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'bride', 'govtIssuedId')" accept="image/*,.pdf" />
                          <div class="glass-placeholder border-glass rounded-3 p-3 text-center">
                            <div v-if="!previews.bride.govtIssuedId">
                              <i class="bi bi-cloud-arrow-up fs-3 text-white"></i>
                              <p class="mb-0 smallest text-white">Upload Bride Government Issued ID</p>
                            </div>
                            <div v-else class="animate-fade-in">
                              <img v-if="isImage('bride', 'govtIssuedId')" :src="previews.bride.govtIssuedId" class="img-fluid rounded-2 glass-preview-img-sm" />
                              <div v-else class="py-2"><i class="bi bi-file-earmark-pdf fs-2 text-info"></i></div>
                              <div class="glass-badge-sm">Selected</div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="mb-4">
                        <label class="form-label fw-semibold small text-uppercase text-white-50">PMOC Certificate <span class="text-info">*</span></label>
                        <div class="glass-upload-container position-relative">
                          <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'bride', 'pmocCertificate')" accept="image/*,.pdf" />
                          <div class="glass-placeholder border-glass rounded-3 p-3 text-center">
                            <div v-if="!previews.bride.pmocCertificate">
                              <i class="bi bi-cloud-arrow-up fs-3 text-white"></i>
                              <p class="mb-0 smallest text-white">Upload Bride PMOC Certificate</p>
                            </div>
                            <div v-else class="animate-fade-in">
                              <img v-if="isImage('bride', 'pmocCertificate')" :src="previews.bride.pmocCertificate" class="img-fluid rounded-2 glass-preview-img-sm" />
                              <div v-else class="py-2"><i class="bi bi-file-earmark-pdf fs-2 text-info"></i></div>
                              <div class="glass-badge-sm">Selected</div>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>

                <!-- NAVIGATION -->
                <div class="d-flex justify-content-between mt-5">
                  <button class="btn btn-outline-light"
                          :disabled="step === 1"
                          @click="goBack()">
                    Back
                  </button>

                  <button v-if="step < totalSteps && step != 27"
                          class="btn btn-primary glow-button"
                          @click="goNext()">
                    Next
                  </button>

                  <button v-else-if="step == 27">

                    <button class="btn btn-success glow-button"
                            @click="goNext()">
                      Proceed to Required Documents
                    </button>

                  </button>

                  <button v-else
                          class="btn btn-success glow-button"
                          @click="submitForm">
                    Submit
                  </button>
                </div>

                

              </div>
            </div>

          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import WarningForm from '../../../components/WarningForm.vue';

export default {
  name: 'MarriageFormSteps',
  components: {
    WarningForm,
  },
  data() {
    return {
      message: [],
      step: 1,
      totalSteps: 28,
      ageError: '',
      isPreviouslyMarried: false,
      previews: {
        groom: { cenomar: null, psa: null, lcro: null },
        bride: { cenomar: null, psa: null, lcro: null }
      },
      fileTypes: {
        groom: { cenomar: '', psa: '', lcro: '' },
        bride: { cenomar: '', psa: '', lcro: '' }
      },
      form: {
        groom: { documents: {} },
        bride: { documents: {} }
      }
    }
  },
  watch: {
    // We watch the whole form but filter logic inside the method
    'form.groom': {
      handler(val) { this.handlePersonValidation(val, 'groom'); },
      deep: true
    },
    'form.bride': {
      handler(val) { this.handlePersonValidation(val, 'bride'); },
      deep: true
    }
  },

  methods: {
    handleFileUpload(event, person, docType) {
      const file = event.target.files[0];
      if (!file) return;

      // Store file in the form object
      this.form[person].documents[docType] = file;
      this.fileTypes[person][docType] = file.type;

      if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.previews[person][docType] = e.target.result;
        };
        reader.readAsDataURL(file);
      } else {
        // For PDFs or other files, set a placeholder to show icon
        this.previews[person][docType] = 'document';
      }
    },
    isImage(person, docType) {
      const type = this.fileTypes[person][docType];
      return type && type.startsWith('image/');
    },
    goBack(){
      this.step--
    },
    goNext() {
      // Clear any existing messages before starting new validation
      this.message = [];

      // Validation on every step
      switch (this.step) {
        /* --- GROOM VALIDATION --- */
        case 1: // Groom Name
          if (!this.form.groom.firstName || !this.form.groom.lastName) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          if (!this.form.groom.middleName) this.form.groom.middleName = "N/A";
          break;

        case 2: // Groom Birth Date
          if (!this.form.groom.day || !this.form.groom.month || !this.form.groom.year) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          const gYear = parseInt(this.form.groom.year);
          const currentYear = new Date().getFullYear();
          if (gYear < 1900 || gYear > currentYear) {
            this.message.push("Please enter a valid 4-digit year (e.g., 1995).");
            this.scrollToError();
            return;
          }
          const gBirthDate = new Date(gYear, parseInt(this.form.groom.month) - 1, parseInt(this.form.groom.day));
          if (gBirthDate.getFullYear() !== gYear || gBirthDate.getMonth() !== parseInt(this.form.groom.month) - 1) {
            this.message.push("The date entered is invalid. Please check the day and month.");
            this.scrollToError();
            return;
          }
          // Use the age calculated by your watcher/handler
          if (this.form.groom.age < 18) {
            this.message.push("The groom must be at least 18 years old.");
            this.scrollToError();
            return;
          }
          break;

        case 3: // Groom Birth Place
          if (!this.form.groom.cityMunicipality || !this.form.groom.province || !this.form.groom.country) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 4: // Groom Personal Info
          if (!this.form.groom.age || !this.form.groom.sex || !this.form.groom.citizenship) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 5: // Groom Residence
          if (!this.form.groom.residence) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 6: // Groom Religion
          if (!this.form.groom.religion) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 7: // Groom Civil Status
          if (!this.form.groom.civilStatus) {
            this.message.push("Please fill in Civil Status.");
            this.scrollToError();
            return;
          }
          if (!this.form.groom.previousMarriageDissolve) this.form.groom.previousMarriageDissolve = "N/A";
          if (!this.form.groom.dissolvedCityMunicipality) this.form.groom.dissolvedCityMunicipality = "N/A";
          if (!this.form.groom.dissolvedProvince) this.form.groom.dissolvedProvince = "N/A";
          if (!this.form.groom.dissolvedCountry) this.form.groom.dissolvedCountry = "N/A";
          if (!this.form.groom.dissolvedDay) this.form.groom.dissolvedDay = "N/A";
          if (!this.form.groom.dissolvedMonth) this.form.groom.dissolvedMonth = "N/A";
          if (!this.form.groom.dissolvedYear) this.form.groom.dissolvedYear = "N/A";
          break;

        case 8: // Groom Degree
          if (!this.form.groom.degree) this.form.groom.degree = "N/A";
          break;

        case 9: // Groom Father Info
          if (!this.form.groom.fatherFirstName || !this.form.groom.fatherLastName || !this.form.groom.fatherCitizenship) {
            this.message.push("Please fill in all required fields excluding Middle Name.");
            this.scrollToError();
            return;
          }
          if (!this.form.groom.fatherMiddleName) this.form.groom.fatherMiddleName = "N/A";
          break;

        case 10: // Groom Father Residence
          if (!this.form.groom.fatherResidence) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 11: // Groom Mother Info
          if (!this.form.groom.motherMaidenFirstName || !this.form.groom.motherMaidenLastName || !this.form.groom.motherMaidenCitizenship) {
            this.message.push("Please fill in all required fields excluding Middle Name.");
            this.scrollToError();
            return;
          }
          if (!this.form.groom.motherMaidenMiddleName) this.form.groom.motherMaidenMiddleName = "N/A";
          break;

        case 12: // Groom Mother Residence
          if (!this.form.groom.motherMaidenResidence) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        /* --- BRIDE VALIDATION --- */
        case 14: // Bride Name
          if (!this.form.bride.firstName || !this.form.bride.lastName) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          if (!this.form.bride.middleName) this.form.bride.middleName = "N/A";
          break;

        case 15: // Bride Birth Date
          if (!this.form.bride.day || !this.form.bride.month || !this.form.bride.year) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          const bYear = parseInt(this.form.bride.year);
          if (bYear < 1900 || bYear > new Date().getFullYear()) {
            this.message.push("Please enter a valid 4-digit year.");
            this.scrollToError();
            return;
          }
          const bBirthDate = new Date(bYear, parseInt(this.form.bride.month) - 1, parseInt(this.form.bride.day));
          if (bBirthDate.getFullYear() !== bYear) {
            this.message.push("The date entered is invalid.");
            this.scrollToError();
            return;
          }
          if (this.form.bride.age < 18) {
            this.message.push("The bride must be at least 18 years old.");
            this.scrollToError();
            return;
          }
          break;

        case 16: // Bride Birth Place
          if (!this.form.bride.cityMunicipality || !this.form.bride.province || !this.form.bride.country) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 17: // Bride Personal Info
          if (!this.form.bride.age || !this.form.bride.sex || !this.form.bride.citizenship) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 18: // Bride Residence
          if (!this.form.bride.residence) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 19: // Bride Religion
          if (!this.form.bride.religion) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 20: // Bride Civil Status
          if (!this.form.bride.civilStatus) {
            this.message.push("Please fill in Civil Status.");
            this.scrollToError();
            return;
          }
          if (!this.form.bride.previousMarriageDissolve) this.form.bride.previousMarriageDissolve = "N/A";
          if (!this.form.bride.dissolvedCityMunicipality) this.form.bride.dissolvedCityMunicipality = "N/A";
          if (!this.form.bride.dissolvedProvince) this.form.bride.dissolvedProvince = "N/A";
          if (!this.form.bride.dissolvedCountry) this.form.bride.dissolvedCountry = "N/A";
          if (!this.form.bride.dissolvedDay) this.form.bride.dissolvedDay = "N/A";
          if (!this.form.bride.dissolvedMonth) this.form.bride.dissolvedMonth = "N/A";
          if (!this.form.bride.dissolvedYear) this.form.bride.dissolvedYear = "N/A";
          break;

        case 21: // Bride Degree
          if (!this.form.bride.degree) this.form.bride.degree = "N/A";
          break;

        case 22: // Bride Father Info
          if (!this.form.bride.fatherFirstName || !this.form.bride.fatherLastName || !this.form.bride.fatherCitizenship) {
            this.message.push("Please fill in all required fields excluding Middle Name.");
            this.scrollToError();
            return;
          }
          if (!this.form.bride.fatherMiddleName) this.form.bride.fatherMiddleName = "N/A";
          break;

        case 23: // Bride Father Residence
          if (!this.form.bride.fatherResidence) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 24: // Bride Mother Info
          if (!this.form.bride.motherMaidenFirstName || !this.form.bride.motherMaidenLastName || !this.form.bride.motherMaidenCitizenship) {
            this.message.push("Please fill in all required fields excluding Middle Name.");
            this.scrollToError();
            return;
          }
          if (!this.form.bride.motherMaidenMiddleName) this.form.bride.motherMaidenMiddleName = "N/A";
          break;

        case 25: // Bride Mother Residence
          if (!this.form.bride.motherMaidenResidence) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 28: // Documents
          const requiredDocs = ['cenomar', 'psa', 'govtIssuedId', 'pmocCertificate'];
          let missingGroom = requiredDocs.some(doc => !this.form.groom.documents[doc]);
          let missingBride = requiredDocs.some(doc => !this.form.bride.documents[doc]);

          if (missingGroom || missingBride) {
            this.message.push("Please upload all required documents for both Groom and Bride.");
            this.scrollToError();
            return;
          }
          break;

        default:
          break;
      }

      // If validation passes, move to next step
      this.step++;
    },
    handlePersonValidation(person, type) {
      const today = new Date();
      const currentYear = today.getFullYear();
      
      // 1. Force Basic Bounds (Day/Month)
      if (person.day > 31) person.day = 31;
      if (person.month > 12) person.month = 12;

      // 2. Strict Year Handling
      if (person.year) {
        let yearStr = person.year.toString();

        // Prevent typing more than 4 digits
        if (yearStr.length > 4) {
          person.year = parseInt(yearStr.substring(0, 4));
          return; // Stop here and let the watcher re-run with the 4-digit year
        }

        // Only validate once they've finished typing all 4 digits
        if (yearStr.length === 4) {
          const minAgeYear = currentYear - 18;   // 2008
          const maxAgeYear = currentYear - 120;  // 1906

          // Correct out-of-bounds years
          if (person.year > minAgeYear) {
            person.year = minAgeYear;
            alert("Contracting parties must be at least 18 years old.");
          } else if (person.year < maxAgeYear) {
            person.year = maxAgeYear;
            alert("Please enter a valid year (Max 120 years old).");
          }

          // 3. Auto-Calculate Age
          const birthDate = new Date(person.year, (person.month || 1) - 1, person.day || 1);
          let calculatedAge = today.getFullYear() - birthDate.getFullYear();
          const m = today.getMonth() - birthDate.getMonth();
          if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            calculatedAge--;
          }
          person.age = calculatedAge;
        } else {
          // Clear age if the year is incomplete (e.g., user is still typing)
          person.age = '';
        }
      }

      // For previously married logic
      if(type === 'groom' && person.civilStatus && person.civilStatus.toLowerCase() !== 'single') {
        this.isPreviouslyMarried = true;
      } else if(type === 'bride' && person.civilStatus && person.civilStatus.toLowerCase() !== 'single') {
        this.isPreviouslyMarried = true;
      } else {
        this.isPreviouslyMarried = false;
      }

    },

    submitForm() {
      this.message = [];
      const groomDocs = this.form.groom.documents;
      const brideDocs = this.form.bride.documents;

      // List of required keys based on your HTML template
      const requiredDocs = ['cenomar', 'psa', 'govtIssuedId', 'pmocCertificate'];
      let missingGroom = false;
      let missingBride = false;

      // Check Groom
      requiredDocs.forEach(doc => {
        if (!groomDocs[doc]) missingGroom = true;
      });

      // Check Bride
      requiredDocs.forEach(doc => {
        if (!brideDocs[doc]) missingBride = true;
      });

      if (missingGroom || missingBride) {
        this.message.push("Please upload all required documents for both Groom and Bride.");
        this.scrollToError();
        return;
      }
      console.log(this.form);
      alert('Application Submitted!');
    },
    scrollToError() {
      this.$nextTick(() => {
        // We use $nextTick to ensure the DOM has updated with the new error message
        const element = this.$refs.errorContainer;
        if (element) {
          element.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      });
    }
  }
}
</script>


<style scoped>
/* MAIN CONTAINER & BACKGROUND */
.main-container {
  font-family: 'Inter', sans-serif;
  background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), 
              url('../../../../../public/background.jpg'); 
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
}

/* GLASS CARD STYLING */
.glass-card {
  background: rgba(255, 255, 255, 0.1) !important; /* Highly transparent */
  backdrop-filter: blur(20px) saturate(160%);
  -webkit-backdrop-filter: blur(20px) saturate(160%);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 24px;
}

.card-header {
  background: rgba(30, 60, 114, 0.5) !important; /* Darker tinted top */
  border-radius: 24px 24px 0 0 !important;
}

/* TYPOGRAPHY */
.section-title {
  color: #ffffff;
  font-weight: 700;
  border-left: 4px solid #0d6efd;
  padding-left: 15px;
  text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.form-label {
  color: rgba(255, 255, 255, 0.9);
  font-size: 0.85rem;
  font-weight: 600;
  margin-bottom: 8px;
}

.text-shadow-medium {
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
}

/* FORM INPUTS */
.form-control {
  background: rgba(255, 255, 255, 0.9) !important; /* Almost white for readability */
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-radius: 12px;
  padding: 12px 15px;
  transition: all 0.3s ease;
}

.form-control:focus {
  background: #ffffff !important;
  box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.25);
  transform: translateY(-2px);
}

/* BADGES FOR CONTEXT */
.context-badge {
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.1);
  padding: 10px;
  border-radius: 12px;
  color: white;
  text-align: center;
}

/* BUTTONS */
.glow-button {
  box-shadow: 0 0 20px rgba(13, 110, 253, 0.4);
  transition: all 0.3s ease;
}

.glow-button:hover {
  box-shadow: 0 0 30px rgba(13, 110, 253, 0.6);
  transform: scale(1.02);
}

.back-link {
  transition: all 0.2s ease;
}

.back-link:hover {
  opacity: 1 !important;
  transform: translateX(-5px);
}

/* GLASS INNER CONTAINERS */
.glass-inner {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(5px);
  -webkit-backdrop-filter: blur(5px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 15px;
  box-shadow: inset 0 0 15px rgba(0, 0, 0, 0.2);
}

.review-scroll-area {
  max-height: 400px;
  overflow-y: auto;
  padding: 15px;
}

/* TABLE STYLING */
.review-table td {
  padding: 12px 8px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  vertical-align: middle;
}

.table-header-row th {
  background: rgba(13, 110, 253, 0.15);
  color: #fff;
  font-size: 0.75rem;
  letter-spacing: 1.5px;
  padding: 8px 10px;
  border-radius: 5px;
}

.glow-badge {
  box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
  padding: 6px 12px;
}

/* FINAL REVIEW TEXT */
.final-review-list p {
  margin-bottom: 8px;
  font-size: 0.85rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.03);
  padding-bottom: 4px;
}

/* SCROLLBAR CUSTOMIZATION */
.review-scroll-area::-webkit-scrollbar {
  width: 6px;
}
.review-scroll-area::-webkit-scrollbar-track {
  background: transparent;
}
.review-scroll-area::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.2);
  border-radius: 10px;
}

/* Container for the upload area */
.glass-upload-container {
  overflow: hidden;
  transition: all 0.3s ease;
}

/* The Frosted Box */
.glass-placeholder {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 2px dashed rgba(255, 255, 255, 0.15);
  min-height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

/* Hover Effect */
.glass-upload-container:hover .glass-placeholder {
  background: rgba(255, 255, 255, 0.1);
  border-color: rgba(13, 110, 253, 0.5); /* Primary Blue glow */
  transform: translateY(-2px);
}

/* Hidden File Input */
.file-input-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  cursor: pointer;
  z-index: 10;
}

/* Glass Badge */
.glass-badge {
  position: absolute;
  bottom: 10px;
  right: 10px;
  background: rgba(13, 110, 253, 0.6);
  backdrop-filter: blur(5px);
  color: white;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.7rem;
  font-weight: bold;
  letter-spacing: 1px;
  text-transform: uppercase;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Preview Image Adjustments */
.glass-preview-img {
  max-height: 180px;
  border: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
}

.smallest {
  font-size: 0.75rem;
}
.glass-preview-img-sm {
  max-height: 100px; /* Reduced from 180px */
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.glass-placeholder {
  min-height: 120px !important; /* Smaller footprint */
}

.glass-badge-sm {
  position: absolute;
  bottom: 5px;
  right: 5px;
  background: rgba(13, 110, 253, 0.8);
  color: white;
  padding: 2px 8px;
  border-radius: 10px;
  font-size: 0.6rem;
  text-transform: uppercase;
}

.border-white-10 {
  border-color: rgba(255, 255, 255, 0.1) !important;
}
</style>