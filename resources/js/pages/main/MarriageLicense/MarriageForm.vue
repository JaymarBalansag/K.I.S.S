<template>
  <div class="main-container min-vh-100 d-flex flex-column">
    <main class="flex-grow-1 py-5 mt-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-9">

            <div class="text-start mb-4">
              <a @click.prevent="$router.back()" class="text-white text-decoration-none opacity-75 hover-opacity-100" style="cursor: pointer;">
                <span class="fs-5">&larr;</span> Back
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

                <div v-if="step > 1" class="alert alert-light bg-white bg-opacity-10 border-0 mb-4 py-2">
                  <small class="text-white-50"><i class="bi bi-save me-1"></i> Your progress is being automatically saved as you go to next step.</small>
                </div>

                <!-- STEP 1 – Marriage Required Documents -->
                <div v-if="step === 1" class="glass-panel p-4">
                  <h4 class="section-title text-white mb-4">Required Documents</h4>
                  
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>

                  <div class="document-grid-container">
                    <div class="row mb-3 d-none d-md-flex">
                      <div class="col-md-6 text-center"><span class="badge bg-primary text-uppercase px-3 py-2">Groom's Files</span></div>
                      <div class="col-md-6 text-center"><span class="badge bg-info text-dark text-uppercase px-3 py-2">Bride's Files</span></div>
                    </div>

                    <div v-for="doc in ['cenomar', 'psa', 'govtIssuedId', 'pmocCertificate']" :key="doc" class="row g-4 mb-5 border-bottom border-white-10 pb-4">
                      <div class="col-12">
                        <label class="form-label fw-bold small text-uppercase text-info tracking-wider">
                          {{ doc === 'govtIssuedId' ? 'Government Issued ID' : doc === 'pmocCertificate' ? 'PMOC Certificate' : doc.toUpperCase() }}
                          <span class="text-danger">*</span>
                        </label>
                      </div>
                      
                      <div class="col-md-6 border-md-end border-white-10">
                        <div class="glass-upload-container position-relative">
                          <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'groom', doc)" accept="image/*,.pdf" />
                          <div class="glass-placeholder border-glass rounded-3 p-3 text-center" :class="{'has-file': previews.groom[doc]}">
                            <div v-if="!previews.groom[doc]">
                              <i class="bi bi-cloud-arrow-up fs-3 text-white-50"></i>
                              <p class="mb-0 smallest text-white-50">Upload Groom {{ doc.toUpperCase() }}</p>
                            </div>
                            <div v-else class="animate-fade-in">
                              <img v-if="isImage('groom', doc)" :src="previews.groom[doc]" class="img-fluid rounded-2 glass-preview-img-sm" />
                              <div v-else class="py-2"><i class="bi bi-file-earmark-pdf fs-2 text-info"></i></div>
                              <div class="glass-badge-sm">Selected</div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="glass-upload-container position-relative">
                          <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'bride', doc)" accept="image/*,.pdf" />
                          <div class="glass-placeholder border-glass rounded-3 p-3 text-center" :class="{'has-file': previews.bride[doc]}">
                            <div v-if="!previews.bride[doc]">
                              <i class="bi bi-cloud-arrow-up fs-3 text-white-50"></i>
                              <p class="mb-0 smallest text-white-50">Upload Bride {{ doc.toUpperCase() }}</p>
                            </div>
                            <div v-else class="animate-fade-in">
                              <img v-if="isImage('bride', doc)" :src="previews.bride[doc]" class="img-fluid rounded-2 glass-preview-img-sm" />
                              <div v-else class="py-2"><i class="bi bi-file-earmark-pdf fs-2 text-info"></i></div>
                              <div class="glass-badge-sm">Selected</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div v-if="!(brideRequirement == 'no-need' && groomRequirement == 'no-need')" class="mt-5 animate-fade-in">
                      <h5 class="text-info small fw-bold text-uppercase mb-4"><i class="bi bi-people me-2"></i> Parental Requirements (By Age)</h5>
                      <div class="row g-4 border-bottom border-white-10 pb-4">
  
                      <div :class="brideRequirement === 'no-need' ? 'col-12' : 'col-md-6 border-md-end border-white-10'">
                        
                        <div v-if="groomRequirement == 'parental-consent'" class="mb-3">
                          <label class="form-label fw-bold small text-info text-uppercase">Parental Consent (Groom)</label>
                          <div class="glass-upload-container position-relative">
                            <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'groom', 'parentalConsent')" accept="image/*,.pdf" />
                            <div class="glass-placeholder border-glass rounded-3 p-3 text-center" :class="{'has-file': previews.groom.parentalConsent}">
                              <div v-if="!previews.groom.parentalConsent">
                                <i class="bi bi-file-earmark-arrow-up text-white-50"></i>
                                <p class="smallest text-white-50">Upload Consent</p>
                              </div>
                              <div v-else class="text-info small">Consent Loaded</div>
                            </div>
                          </div>
                        </div>

                        <div v-if="groomRequirement == 'parental-advice'" class="mb-3">
                          <label class="form-label fw-bold small text-info text-uppercase">Parental Advice (Groom)</label>
                          <div class="glass-upload-container position-relative">
                            <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'groom', 'parentalAdvice')" accept="image/*,.pdf" />
                            <div class="glass-placeholder border-glass rounded-3 p-3 text-center" :class="{'has-file': previews.groom.parentalAdvice}">
                              <div v-if="!previews.groom.parentalAdvice">
                                <i class="bi bi-file-earmark-arrow-up text-white-50"></i>
                                <p class="smallest text-white-50">Upload Advice</p>
                              </div>
                              <div v-else class="text-info small">Advice Loaded</div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div :class="groomRequirement === 'no-need' ? 'col-12' : 'col-md-6'">
                        
                        <div v-if="brideRequirement == 'parental-consent'" class="mb-3">
                          <label class="form-label fw-bold small text-info text-uppercase">Parental Consent (Bride)</label>
                          <div class="glass-upload-container position-relative">
                            <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'bride', 'parentalConsent')" accept="image/*,.pdf" />
                            <div class="glass-placeholder border-glass rounded-3 p-3 text-center" :class="{'has-file': previews.bride.parentalConsent}">
                              <div v-if="!previews.bride.parentalConsent">
                                <i class="bi bi-file-earmark-arrow-up text-white-50"></i>
                                <p class="smallest text-white-50">Upload Consent</p>
                              </div>
                              <div v-else class="text-info small">Consent Loaded</div>
                            </div>
                          </div>
                        </div>

                        <div v-if="brideRequirement == 'parental-advice'" class="mb-3">
                          <label class="form-label fw-bold small text-info text-uppercase">Parental Advice (Bride)</label>
                          <div class="glass-upload-container position-relative">
                            <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'bride', 'parentalAdvice')" accept="image/*,.pdf" />
                            <div class="glass-placeholder border-glass rounded-3 p-3 text-center" :class="{'has-file': previews.bride.parentalAdvice}">
                              <div v-if="!previews.bride.parentalAdvice">
                                <i class="bi bi-file-earmark-arrow-up text-white-50"></i>
                                <p class="smallest text-white-50">Upload Advice</p>
                              </div>
                              <div v-else class="text-info small">Advice Loaded</div>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                    </div>

                      <div v-if="type !== 'filipino'" class="mt-4 animate-fade-in">
                        <h5 class="text-warning small fw-bold text-uppercase mb-4"><i class="bi bi-globe me-2"></i> Foreigner Requirements</h5>
                        <div class="row g-4">
                          <div v-if="type === 'groom' || type === 'both'" class="col-md-6 border-md-end border-white-10">
                            <div v-for="fDoc in [{k:'legalCapacity', l:'Legal Capacity'}, {k:'validPassport', l:'Passport / ACR Card'}]" :key="fDoc.k" class="mb-4">
                              <label class="form-label fw-bold small text-uppercase text-info">{{ fDoc.l }}</label>
                              <div class="glass-upload-container position-relative">
                                <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'groom', fDoc.k)" accept="image/*,.pdf" />
                                <div class="glass-placeholder border-glass rounded-3 p-3 text-center" :class="{'has-file': previews.groom[fDoc.k]}">
                                  <div v-if="!previews.groom[fDoc.k]">
                                    <i class="bi bi-cloud-arrow-up text-white-50"></i>
                                    <p class="mb-0 smallest text-white-50">Upload {{ fDoc.l }}</p>
                                  </div>
                                  <div v-else class="text-info small">Selected</div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div v-if="type === 'bride' || type === 'both'" class="col-md-6">
                            <div v-for="fDoc in [{k:'legalCapacity', l:'Legal Capacity'}, {k:'validPassport', l:'Passport / ACR Card'}]" :key="fDoc.k" class="mb-4">
                              <label class="form-label fw-bold small text-uppercase text-info">{{ fDoc.l }}</label>
                              <div class="glass-upload-container position-relative">
                                <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'bride', fDoc.k)" accept="image/*,.pdf" />
                                <div class="glass-placeholder border-glass rounded-3 p-3 text-center" :class="{'has-file': previews.bride[fDoc.k]}">
                                  <div v-if="!previews.bride[fDoc.k]">
                                    <i class="bi bi-cloud-arrow-up text-white-50"></i>
                                    <p class="mb-0 smallest text-white-50">Upload {{ fDoc.l }}</p>
                                  </div>
                                  <div v-else class="text-info small">Selected</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
              </div>

                <!-- STEP 2 - GROOM INFO -->
                <div v-if="step === 2">
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>

                  <div class="glass-inner p-4 mb-4">
                    <h4 class="section-title mb-4">Groom's Primary Information</h4>
                    
                    <div class="row g-3 mb-4">
                      <div class="col-md-4">
                        <label class="form-label">First Name</label>
                        <input class="form-control" v-model="form.groom.firstName" placeholder="Enter first name">
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input class="form-control" v-model="form.groom.middleName" placeholder="Enter middle name">
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Last Name</label>
                        <input class="form-control" v-model="form.groom.lastName" placeholder="Enter last name">
                      </div>
                    </div>

                    <div class="row g-3">
                      <div class="col-md-6">
                        <label class="form-label">Birth Date</label>
                        <div class="input-group">
                          <input type="number" class="form-control" v-model="form.groom.day" placeholder="DD">
                          <input type="number" class="form-control" v-model="form.groom.month" placeholder="MM">
                          <input type="number" class="form-control" v-model="form.groom.year" placeholder="YYYY">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <label class="form-label">Age</label>
                        <input class="form-control bg-light text-dark" v-model="form.groom.age" readonly placeholder="Auto">
                        <small v-if="ageError" class="text-danger fw-bold smallest">{{ ageError }}</small>
                      </div>
                      <div class="col-md-3">
                        <label class="form-label">Sex</label>
                        <input class="form-control bg-light text-dark" v-model="form.groom.sex" readonly placeholder="Auto">
                      </div>
                    </div>
                  </div>

                  <div class="glass-inner p-4 mb-4">
                    <h4 class="section-title mb-4">Origin & Legal Status</h4>
                    
                    <div class="row g-3 mb-3">
                      <div class="col-md-4">
                        <label class="form-label">Birth City / Municipality</label>
                        <div class="position-relative">
                          <input
                            class="form-control"
                            v-model="form.groom.cityMunicipality"
                            @input="onGroomCityInput"
                            @focus="onGroomCityFocus"
                            @blur="onGroomCityBlur"
                            autocomplete="off"
                            placeholder="Type city / municipality"
                          >
                          <div
                            v-if="showGroomCitySuggestions && groomCitySuggestions.length"
                            class="city-suggestion-menu"
                          >
                            <button
                              v-for="suggestion in groomCitySuggestions"
                              :key="suggestion.id"
                              type="button"
                              class="city-suggestion-item"
                              @mousedown.prevent="selectGroomCitySuggestion(suggestion)"
                            >
                              <span class="city-suggestion-name">{{ suggestion.cityMunicipality }}</span>
                              <span v-if="suggestion.context" class="city-suggestion-context">{{ suggestion.context }}</span>
                            </button>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Birth Province</label>
                        <input class="form-control" v-model="form.groom.province">
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Birth Country</label>
                        <input class="form-control" v-model="form.groom.country">
                      </div>
                    </div>

                    <div class="row g-3">
                      <div class="col-md-4">
                        <label class="form-label">Citizenship</label>
                        <select class="form-control" disabled v-model="form.groom.citizenship">
                          <option value="" disabled>Select Citizenship</option>
                          <option value="Filipino">Filipino</option>
                          <option value="Others">Others</option>
                        </select>
                      </div>
                      <div v-if="form.groom.citizenship === 'Others'" class="col-md-8">
                        <label class="form-label">Other Citizenship</label>
                        <input class="form-control" v-model="form.groom.citizenshipOther">
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Religion / Sect</label>
                        <input class="form-control" v-model="form.groom.religion">
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Civil Status</label>
                        <select class="form-control" v-model="form.groom.civilStatus">
                          <option value="Single">Single</option>
                          <option value="Widowed">Widowed</option>
                          <option value="Annulled">Annulled</option>
                          <option value="Divorced">Divorced</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div v-if="isPreviouslyMarried" class="glass-inner p-4 mb-4 border border-info">
                    <h4 class="section-title mb-4">Dissolution of Previous Marriage</h4>
                    <div class="row g-3">
                      <div class="col-md-12">
                        <label class="form-label">How was it dissolved?</label>
                        <textarea class="form-control" rows="2" v-model="form.groom.previousMarriageDissolve"></textarea>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Place where dissolved</label>
                        <input class="form-control" v-model="form.groom.dissolvedCityMunicipality" placeholder="City/Province/Country">
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Date when dissolved</label>
                        <div class="input-group">
                          <input type="number" class="form-control" v-model="form.groom.dissolvedDay" placeholder="Day">
                          <input type="number" class="form-control" v-model="form.groom.dissolvedMonth" placeholder="Month">
                          <input type="number" class="form-control" v-model="form.groom.dissolvedYear" placeholder="Year">
                        </div>
                      </div>
                    </div>
                    <div v-if="['Widowed', 'Annulled', 'Divorced'].includes(form.groom.civilStatus) && type === 'groom'" 
                        class="row g-4 mt-3 mb-5 border-bottom border-white-10 pb-4">
                      
                      <div class="col-12">
                        <div class="mb-3">
                          <label class="form-label fw-bold small text-info text-uppercase">
                            Groom's {{ form.groom.civilStatus === 'Widowed' ? 'Apostilled Foreign Death Cert' : 'Divorce / Anullment Decree' }}
                          </label>

                          <div v-if="form.groom.civilStatus === 'Widowed'" 
                              key="upload-widowed" 
                              class="glass-upload-container position-relative">
                            <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'groom', 'appostilled')" accept="image/*,.pdf" />
                            <div class="glass-placeholder border-glass rounded-3 p-3 text-center" :class="{'has-file': previews.groom.appostilled}">
                              <div v-if="!previews.groom.appostilled">
                                <i class="bi bi-file-earmark-arrow-up text-white-50"></i>
                                <p class="smallest text-white-50">Upload Death Certificate</p>
                              </div>
                              <div v-else class="text-info small">Death Cert Loaded</div>
                            </div>
                          </div>

                          <div v-if="form.groom.civilStatus === 'Divorced' || form.groom.civilStatus === 'Annulled'" 
                              key="upload-divorced" 
                              class="glass-upload-container position-relative">
                            <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'groom', 'divorceDecree')" accept="image/*,.pdf" />
                            <div class="glass-placeholder border-glass rounded-3 p-3 text-center" :class="{'has-file': previews.groom.divorceDecree}">
                              <div v-if="!previews.groom.divorceDecree">
                                <i class="bi bi-file-earmark-arrow-up text-white-50"></i>
                                <p class="smallest text-white-50">Upload Divorce Decree</p>
                              </div>
                              <div v-else class="text-info small">Divorce Decree Loaded</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="glass-inner p-4 mb-4">
                    <h4 class="section-title mb-3">Residence & Contact Info</h4>
                    <div class="row g-3">
                      <div class="col-md-12">
                        <label class="form-label">Full Address</label>
                        <input class="form-control" v-model="form.groom.residence" placeholder="House No., Street, City/Municipality, Province, Country">
                        <small class="text-white-50 smallest mt-2 d-block italic"><i class="bi bi-info-circle me-1"></i>Tips: Please enter complete details for legal records.</small>
                      </div>
                      <div class="col-md-12 mt-3">
                        <label class="form-label">Degree of relationship (Contracting Parties)</label>
                        <input class="form-control" v-model="form.groom.degree">
                        <small class="text-white-50 smallest">Leave blank if not applicable.</small>
                      </div>
                    </div>
                  </div>

                  <div class="glass-inner p-4 mb-4">
                    <h4 class="section-title mb-4">Parents' Information</h4>
                    
                    <div class="row g-3 mb-4 border-bottom border-white-10 pb-4">
                      <div class="col-12"><small class="text-white-50">Father's Details</small></div>
                      <div class="col-md-4"><input class="form-control" v-model="form.groom.fatherFirstName" placeholder="First Name"></div>
                      <div class="col-md-4"><input class="form-control" v-model="form.groom.fatherMiddleName" placeholder="Middle Name"></div>
                      <div class="col-md-4"><input class="form-control" v-model="form.groom.fatherLastName" placeholder="Last Name"></div>
                      <div class="col-md-4">
                        <select class="form-control" v-model="form.groom.fatherCitizenship">
                          <option value="" disabled>Father's Citizenship</option>
                          <option value="Filipino">Filipino</option>
                          <option value="Foreign National">Others</option>
                        </select>
                      </div>
                      <div class="col-md-8"><input class="form-control" v-model="form.groom.fatherResidence" placeholder="Father's Full Residence"></div>
                    </div>

                    <div class="row g-3">
                      <div class="col-12"><small class="text-white-50">Mother's Maiden Details</small></div>
                      <div class="col-md-4"><input class="form-control" v-model="form.groom.motherMaidenFirstName" placeholder="First Name"></div>
                      <div class="col-md-4"><input class="form-control" v-model="form.groom.motherMaidenMiddleName" placeholder="Middle Name"></div>
                      <div class="col-md-4"><input class="form-control" v-model="form.groom.motherMaidenLastName" placeholder="Last Name"></div>
                      <div class="col-md-4">
                        <select class="form-control" v-model="form.groom.motherMaidenCitizenship">
                          <option value="" disabled>Mother's Citizenship</option>
                          <option value="Filipino">Filipino</option>
                          <option value="Foreign National">Others</option>
                        </select>
                      </div>
                      <div class="col-md-8"><input class="form-control" v-model="form.groom.motherMaidenResidence" placeholder="Mother's Full Residence"></div>
                    </div>
                  </div>

                  <div v-if="groomRequirement !== 'no-need'" class="glass-inner p-4 mb-4 border border-warning">
                    <h4 class="section-title mb-4">Person Providing {{ groomRequirement === 'parental-consent' ? 'Consent' : 'Advice' }}</h4>
                    <div class="row g-3">
                      <div class="col-md-4"><label class="form-label">First Name</label><input class="form-control" v-model="form.consentSource.groom.firstName"></div>
                      <div class="col-md-4"><label class="form-label">Middle Name</label><input class="form-control" v-model="form.consentSource.groom.middleName"></div>
                      <div class="col-md-4"><label class="form-label">Last Name</label><input class="form-control" v-model="form.consentSource.groom.lastName"></div>
                      <div class="col-md-4">
                        <label class="form-label">Citizenship</label>
                        <select class="form-control" v-model="form.consentSource.groom.citizenship">
                          <option value="Filipino">Filipino</option>
                          <option value="Foreign National">Foreign National</option>
                        </select>
                      </div>
                      <div class="col-md-4"><label class="form-label">Relationship</label><input class="form-control" v-model="form.consentSource.groom.relationship" placeholder="e.g. Father"></div>
                      <div class="col-md-12"><label class="form-label">Full Residence</label><input class="form-control" v-model="form.consentSource.groom.residence"></div>
                    </div>
                  </div>

                </div>

                <!-- STEP 3 – BRIDE INFO -->
                <div v-if="step === 3">
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>

                  <div class="glass-inner p-4 mb-4">
                    <h4 class="section-title mb-4">Bride's Primary Information</h4>
                    
                    <div class="row g-3 mb-4">
                      <div class="col-md-4">
                        <label class="form-label">First Name</label>
                        <input class="form-control" v-model="form.bride.firstName" placeholder="Enter first name">
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input class="form-control" v-model="form.bride.middleName" placeholder="Enter middle name">
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Last Name</label>
                        <input class="form-control" v-model="form.bride.lastName" placeholder="Enter last name">
                      </div>
                    </div>

                    <div class="row g-3">
                      <div class="col-md-6">
                        <label class="form-label">Birth Date</label>
                        <div class="input-group">
                          <input type="number" class="form-control" v-model="form.bride.day" placeholder="DD">
                          <input type="number" class="form-control" v-model="form.bride.month" placeholder="MM">
                          <input type="number" class="form-control" v-model="form.bride.year" placeholder="YYYY">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <label class="form-label">Age</label>
                        <input class="form-control bg-light text-dark" v-model="form.bride.age" placeholder="Age">
                      </div>
                      <div class="col-md-3">
                        <label class="form-label">Sex</label>
                        <input class="form-control bg-light text-dark" v-model="form.bride.sex" readonly placeholder="Female">
                      </div>
                    </div>
                  </div>

                  <div class="glass-inner p-4 mb-4">
                    <h4 class="section-title mb-4">Origin & Legal Status</h4>
                    
                    <div class="row g-3 mb-3">
                      <div class="col-md-4">
                        <label class="form-label">Birth City / Municipality</label>
                        <input class="form-control" v-model="form.bride.cityMunicipality">
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Birth Province</label>
                        <input class="form-control" v-model="form.bride.province">
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Birth Country</label>
                        <input class="form-control" v-model="form.bride.country">
                      </div>
                    </div>

                    <div class="row g-3">
                      <div class="col-md-4">
                        <label class="form-label">Citizenship</label>
                        <select class="form-control" v-model="form.bride.citizenship">
                          <option value="" disabled>Select Citizenship</option>
                          <option value="Filipino">Filipino</option>
                          <option value="Foreign National">Foreign National</option>
                        </select>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Religion / Sect</label>
                        <input class="form-control" v-model="form.bride.religion">
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Civil Status</label>
                        <select class="form-control" v-model="form.bride.civilStatus">
                          <option value="Single">Single</option>
                          <option value="Widowed">Widowed</option>
                          <option value="Annulled">Annulled</option>
                          <option value="Divorced">Divorced</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="glass-inner p-4 mb-4">
                    <h4 class="section-title mb-3">Residence & Contact Info</h4>
                    <div class="row g-3">
                      <div class="col-md-12">
                        <label class="form-label">Full Address</label>
                        <input class="form-control" v-model="form.bride.residence" placeholder="House No., Street, City/Municipality, Province, Country">
                        <small class="text-white-50 smallest mt-2 d-block italic"><i class="bi bi-info-circle me-1"></i>Tips: Please enter complete details for legal records.</small>
                      </div>
                      <div class="col-md-12 mt-3">
                        <label class="form-label">Degree of relationship (Contracting Parties)</label>
                        <input class="form-control" v-model="form.bride.degree">
                        <small class="text-white-50 smallest">Leave blank if not applicable.</small>
                      </div>
                    </div>
                  </div>

                  <div v-if="isPreviouslyMarried" class="glass-inner p-4 mb-4 border border-info">
                    <h4 class="section-title mb-4">Dissolution of Previous Marriage</h4>
                    <div class="row g-3">
                      <div class="col-md-12">
                        <label class="form-label">How was it dissolved?</label>
                        <textarea class="form-control" rows="2" v-model="form.bride.previousMarriageDissolve"></textarea>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Place where dissolved</label>
                        <input class="form-control" v-model="form.bride.dissolvedCityMunicipality" placeholder="City/Province/Country">
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Date when dissolved</label>
                        <div class="input-group">
                          <input type="number" class="form-control" v-model="form.bride.dissolvedDay" placeholder="DD">
                          <input type="number" class="form-control" v-model="form.bride.dissolvedMonth" placeholder="MM">
                          <input type="number" class="form-control" v-model="form.bride.dissolvedYear" placeholder="YYYY">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="glass-inner p-4 mb-4">
                    <h4 class="section-title mb-4">Parents' Information</h4>
                    
                    <div class="row g-3 mb-4 border-bottom border-white-10 pb-4">
                      <div class="col-12"><small class="text-white-50 uppercase fw-bold">Father's Details</small></div>
                      <div class="col-md-4"><input class="form-control" v-model="form.bride.fatherFirstName" placeholder="First Name"></div>
                      <div class="col-md-4"><input class="form-control" v-model="form.bride.fatherMiddleName" placeholder="Middle Name"></div>
                      <div class="col-md-4"><input class="form-control" v-model="form.bride.fatherLastName" placeholder="Last Name"></div>
                      <div class="col-md-4">
                        <select class="form-control" v-model="form.bride.fatherCitizenship">
                          <option value="" disabled>Father's Citizenship</option>
                          <option value="Filipino">Filipino</option>
                          <option value="Foreign National">Foreign National</option>
                        </select>
                      </div>
                      <div class="col-md-8"><input class="form-control" v-model="form.bride.fatherResidence" placeholder="Father's Full Residence"></div>
                    </div>

                    <div class="row g-3">
                      <div class="col-12"><small class="text-white-50 uppercase fw-bold">Mother's Maiden Details</small></div>
                      <div class="col-md-4"><input class="form-control" v-model="form.bride.motherMaidenFirstName" placeholder="First Name"></div>
                      <div class="col-md-4"><input class="form-control" v-model="form.bride.motherMaidenMiddleName" placeholder="Middle Name"></div>
                      <div class="col-md-4"><input class="form-control" v-model="form.bride.motherMaidenLastName" placeholder="Last Name"></div>
                      <div class="col-md-4">
                        <select class="form-control" v-model="form.bride.motherMaidenCitizenship">
                          <option value="" disabled>Mother's Citizenship</option>
                          <option value="Filipino">Filipino</option>
                          <option value="Foreign National">Foreign National</option>
                        </select>
                      </div>
                      <div class="col-md-8"><input class="form-control" v-model="form.bride.motherMaidenResidence" placeholder="Mother's Full Residence"></div>
                    </div>
                  </div>

                  <div v-if="brideRequirement !== 'no-need'" class="glass-inner p-4 mb-4 border border-warning">
                    <h4 class="section-title mb-4">Person Providing {{ brideRequirement === 'parental-consent' ? 'Consent' : 'Advice' }}</h4>
                    <div class="row g-3">
                      <div class="col-md-4">
                        <label class="form-label">First Name</label>
                        <input class="form-control" v-model="form.consentSource.bride.firstName">
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input class="form-control" v-model="form.consentSource.bride.middleName">
                      </div>
                      <div class="col-md-4">
                        <label class="form-label">Last Name</label>
                        <input class="form-control" v-model="form.consentSource.bride.lastName">
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Citizenship</label>
                        <select class="form-control" v-model="form.consentSource.bride.citizenship">
                          <option value="Filipino">Filipino</option>
                          <option value="Foreign National">Foreign National</option>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Relationship</label>
                        <input class="form-control" v-model="form.consentSource.bride.relationship" placeholder="e.g. Mother / Guardian">
                      </div>
                      <div class="col-md-12">
                        <label class="form-label">Full Residence</label>
                        <input class="form-control" v-model="form.consentSource.bride.residence" placeholder="Complete address">
                      </div>
                    </div>
                  </div>
                </div>

                <!-- STEP 37 – REVIEW -->
                <div v-if="step === 4">
                  <div class="text-center mb-4">
                    <h4 class="section-title border-0 mb-1">Final Verification</h4>
                    <p class="text-white-50 small">Please review all information before submitting the application.</p>
                  </div>

                  <div class="row g-4">
                    <div class="col-md-6">
                      <div class="glass-inner p-4 h-100 border-primary-subtle border-top border-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                          <h6 class="text-primary-subtle fw-bold text-uppercase tracking-wider small mb-0">
                            <i class="bi bi-person-fill me-2"></i>Groom's Profile
                          </h6>
                          <span class="badge rounded-pill bg-primary px-3">Primary</span>
                        </div>

                        <div class="final-review-content text-white">
                          <div class="mb-4">
                            <label class="text-white-50 smallest text-uppercase d-block">Full Name</label>
                            <h5 class="fw-bold mb-0">{{ form.groom.lastName }}, {{ form.groom.firstName }} {{ form.groom.middleName }}</h5>
                          </div>

                          <div class="row g-3">
                            <div class="col-6">
                              <label class="text-white-50 smallest text-uppercase d-block">Age / Status</label>
                              <p class="mb-0 fw-semibold">{{ form.groom.age }} — {{ form.groom.civilStatus }}</p>
                            </div>
                            <div class="col-6">
                              <label class="text-white-50 smallest text-uppercase d-block">Religion</label>
                              <p class="mb-0 fw-semibold">{{ form.groom.religion || 'N/A' }}</p>
                            </div>
                            <div class="col-12">
                              <label class="text-white-50 smallest text-uppercase d-block">Citizenship</label>
                              <p class="mb-0 fw-semibold">{{ form.groom.citizenship }}</p>
                            </div>
                            <div class="col-12">
                              <div class="p-2 rounded bg-white bg-opacity-10">
                                <label class="text-white-50 smallest text-uppercase d-block">Current Residence</label>
                                <p class="small mb-0 lh-sm">{{ form.groom.residence }}</p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="glass-inner p-4 h-100 border-info-subtle border-top border-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                          <h6 class="text-info-subtle fw-bold text-uppercase tracking-wider small mb-0">
                            <i class="bi bi-person-heart me-2"></i>Bride's Profile
                          </h6>
                          <span class="badge rounded-pill bg-info text-dark px-3">Primary</span>
                        </div>

                        <div class="final-review-content text-white">
                          <div class="mb-4">
                            <label class="text-white-50 smallest text-uppercase d-block">Full Name</label>
                            <h5 class="fw-bold mb-0">{{ form.bride.lastName }}, {{ form.bride.firstName }} {{ form.bride.middleName }}</h5>
                          </div>

                          <div class="row g-3">
                            <div class="col-6">
                              <label class="text-white-50 smallest text-uppercase d-block">Age / Status</label>
                              <p class="mb-0 fw-semibold">{{ form.bride.age }} — {{ form.bride.civilStatus }}</p>
                            </div>
                            <div class="col-6">
                              <label class="text-white-50 smallest text-uppercase d-block">Religion</label>
                              <p class="mb-0 fw-semibold">{{ form.bride.religion || 'N/A' }}</p>
                            </div>
                            <div class="col-12">
                              <label class="text-white-50 smallest text-uppercase d-block">Citizenship</label>
                              <p class="mb-0 fw-semibold">{{ form.bride.citizenship }}</p>
                            </div>
                            <div class="col-12">
                              <div class="p-2 rounded bg-white bg-opacity-10">
                                <label class="text-white-50 smallest text-uppercase d-block">Current Residence</label>
                                <p class="small mb-0 lh-sm">{{ form.bride.residence }}</p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="mt-4 p-3 glass-inner text-center border-0">
                    <p class="text-white-50 small mb-0">
                      <i class="bi bi-shield-check me-2 text-success"></i>
                      By proceeding, you confirm that the information provided above is true and correct to the best of your knowledge.
                    </p>
                  </div>
                </div>

                <!-- NAVIGATION -->
                <div class="d-flex justify-content-between mt-5">
                  <button class="btn btn-outline-light"
                          :disabled="step === 1"
                          @click="goBack()">
                    Back
                  </button>

                  <button v-if="step < totalSteps && step != 4"
                          class="btn btn-primary glow-button"
                          @click="goNext()">
                    Next
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
import { submitMarriageLicenseApplication } from '../../../controller/MarriageLicense';

export default {
  name: 'MarriageFormSteps',
  components: {
    WarningForm,
  },
  data() {
    return {
      isCohabiting: null,
      isForeigner: null,
      type: null,
      groomRequirement: null,
      brideRequirement: null,
      ageRequirements : null,
      message: [],
      step: 2,
      totalSteps: 4,
      ageError: '',
      isPreviouslyMarried: false,
      groomCitySuggestions: [],
      showGroomCitySuggestions: false,
      groomCityDebounceTimer: null,
      groomCityAbortController: null,
      groomCityBlurTimer: null,
      previews: {
        groom: { cenomar: null, psa: null, lcro: null },
        bride: { cenomar: null, psa: null, lcro: null }
      },
      fileTypes: {
        groom: { cenomar: '', psa: '', lcro: '' },
        bride: { cenomar: '', psa: '', lcro: '' }
      },
      form: {
        groom: { documents: {}, sex: "Male" },
        bride: { documents: {}, sex: "Female" },
        consentSource: {
          groom: {},
          bride: {}
        }
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
    getAgeLimits(requirement) {
      switch (requirement) {
        case 'parental-consent':
          return { min: 18, max: 21 };
        case 'parental-advise':
          return { min: 21, max: 25 };
        case 'no-need':
          return { min: 25, max: 120 };
        default:
          return { min: 18, max: 120 };
      }
    },
    getRequirementLabel(requirement) {
      switch (requirement) {
        case 'parental-consent':
          return '18-21';
        case 'parental-advise':
          return '21-25';
        case 'no-need':
          return '25+';
        default:
          return 'valid age';
      }
    },
    isAgeWithinRequirement(age, requirement) {
      const parsedAge = Number(age);
      if (!Number.isFinite(parsedAge)) return false;
      const { min, max } = this.getAgeLimits(requirement);
      return parsedAge >= min && parsedAge <= max;
    },
    getAgeRangeValidationError(age, requirement, personLabel) {
      if (this.isAgeWithinRequirement(age, requirement)) {
        return null;
      }

      if (requirement === 'no-need') {
        return `The ${personLabel} age must be at least 25 based on the selected age bracket.`;
      }

      return `The ${personLabel} age must be within ${this.getRequirementLabel(requirement)} based on the selected age bracket.`;
    },
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
    onGroomCityInput() {
      const query = (this.form.groom.cityMunicipality || '').trim();

      this.showGroomCitySuggestions = true;

      if (this.groomCityDebounceTimer) {
        clearTimeout(this.groomCityDebounceTimer);
      }

      if (this.groomCityAbortController) {
        this.groomCityAbortController.abort();
        this.groomCityAbortController = null;
      }

      if (query.length < 3) {
        this.groomCitySuggestions = [];
        return;
      }

      this.groomCityDebounceTimer = setTimeout(() => {
        this.fetchGroomCitySuggestions(query);
      }, 350);
    },
    async fetchGroomCitySuggestions(query) {
      this.groomCityAbortController = new AbortController();

      try {
        const response = await fetch(
          `https://nominatim.openstreetmap.org/search?format=jsonv2&addressdetails=1&limit=10&dedupe=0&q=${encodeURIComponent(query)}`,
          {
            signal: this.groomCityAbortController.signal,
            headers: { 'Accept-Language': 'en' }
          }
        );

        if (!response.ok) {
          this.groomCitySuggestions = [];
          return;
        }

        const data = await response.json();
        const normalizedQuery = query.toLowerCase();

        const suggestions = data
          .map((item, index) => {
            const address = item.address || {};
            const cityMunicipality =
              address.city ||
              address.town ||
              address.municipality ||
              address.village ||
              address.hamlet ||
              address.county ||
              item.name ||
              '';

            if (!cityMunicipality) return null;

            const province =
              address.state ||
              address.region ||
              address.province ||
              address.state_district ||
              '';
            const country = address.country || '';
            const countryCode = (address.country_code || '').toLowerCase();
            const cityLower = cityMunicipality.toLowerCase();

            let score = 0;
            if (cityLower === normalizedQuery) score += 20;
            if (cityLower.startsWith(normalizedQuery)) score += 10;
            if (countryCode === 'ph') score += 50;

            return {
              id: item.place_id || `${cityMunicipality}-${index}`,
              cityMunicipality,
              province,
              country,
              countryCode,
              context: [province, country].filter(Boolean).join(', '),
              score
            };
          })
          .filter(Boolean)
          .sort((a, b) => b.score - a.score);

        const uniqueByLocation = new Map();
        suggestions.forEach((item) => {
          const key = [
            item.cityMunicipality.toLowerCase(),
            item.province.toLowerCase(),
            item.country.toLowerCase()
          ].join('|');
          if (!uniqueByLocation.has(key)) {
            uniqueByLocation.set(key, item);
          }
        });

        this.groomCitySuggestions = Array.from(uniqueByLocation.values()).slice(0, 6);
      } catch (error) {
        // Ignore canceled requests; clear suggestions for actual request failures.
        if (error.name !== 'AbortError') {
          this.groomCitySuggestions = [];
        }
      } finally {
        this.groomCityAbortController = null;
      }
    },
    onGroomCityFocus() {
      if (this.groomCityBlurTimer) {
        clearTimeout(this.groomCityBlurTimer);
        this.groomCityBlurTimer = null;
      }
      this.showGroomCitySuggestions = this.groomCitySuggestions.length > 0;
    },
    onGroomCityBlur() {
      this.groomCityBlurTimer = setTimeout(() => {
        this.showGroomCitySuggestions = false;
      }, 120);
    },
    selectGroomCitySuggestion(selected) {
      this.form.groom.cityMunicipality = selected.cityMunicipality;
      if (!this.form.groom.province && selected.province) {
        this.form.groom.province = selected.province;
      }
      if (!this.form.groom.country && selected.country) {
        this.form.groom.country = selected.country;
      }
      this.showGroomCitySuggestions = false;
    },
    goBack() {
      let prevStep = this.step - 1;

      // If groom does not require consent/advice source, skip steps 19-20.
      if ((prevStep === 20 || prevStep === 19) && this.groomRequirement === "no-need") {
        prevStep = 18;
      }

      // If bride does not require consent/advice source, skip steps 34-35.
      if ((prevStep === 35 || prevStep === 34) && this.brideRequirement === "no-need") {
        prevStep = 33;
      }

      // If going back to Step 6, but it was skipped (no-need for parental docs)
      if (prevStep === 6 && this.brideRequirement === "no-need" && this.groomRequirement === "no-need") {
        prevStep--;
      }

      // If going back to Step 5, but they are not a foreigner
      const foreignerCheck = String(this.isForeigner) === 'true';
      if (prevStep === 5 && !foreignerCheck) {
        prevStep--;
      }

      this.step = prevStep;
      window.scrollTo(0, 0);
    },
    goNext() {
      // Clear any existing messages before starting new validation
      this.message = [];

      let currentRequiredDocs = [];
      let isGroomMissing = false;
      let isBrideMissing = false;
      // Validation on every step
      switch (this.step) {
        /* --- GROOM VALIDATION --- */
        case 1: // CENOMAR
          currentRequiredDocs = ['cenomar'];
          isGroomMissing = currentRequiredDocs.some(doc => !this.form.groom.documents[doc]);
          isBrideMissing = currentRequiredDocs.some(doc => !this.form.bride.documents[doc]);
          if (isGroomMissing || isBrideMissing) {
            this.message.push("Please upload the CENOMAR for both Groom and Bride.");
          }
          break;

        case 2: // PSA
          currentRequiredDocs = ['psa']; // Re-assigning, not re-declaring
          isGroomMissing = currentRequiredDocs.some(doc => !this.form.groom.documents[doc]);
          isBrideMissing = currentRequiredDocs.some(doc => !this.form.bride.documents[doc]);
          if (isGroomMissing || isBrideMissing) {
            this.message.push("Please upload the Birth Certificate for both Groom and Bride.");
          }
          break;

        case 3: // Govt ID
          currentRequiredDocs = ['govtIssuedId'];
          isGroomMissing = currentRequiredDocs.some(doc => !this.form.groom.documents[doc]);
          isBrideMissing = currentRequiredDocs.some(doc => !this.form.bride.documents[doc]);
          if (isGroomMissing || isBrideMissing) {
            this.message.push("Please upload a Valid ID for both parties.");
          }
          break;

        case 4: // PMOC
          currentRequiredDocs = ['pmocCertificate'];
          isGroomMissing = currentRequiredDocs.some(doc => !this.form.groom.documents[doc]);
          isBrideMissing = currentRequiredDocs.some(doc => !this.form.bride.documents[doc]);
          if (isGroomMissing || isBrideMissing) {
            this.message.push("Please upload the PMOC Certificate.");
          }
          break;

        case 5: // Foreigner Documents
          let missingGroomForeign = false;
          let missingBrideForeign = false;
          const foreignDocs = ['legalCapacity', 'validPassport', 'apostilledOrDecree'];

          // Logic: Only validate these if the person is flagged as a foreigner
          if (this.type === "groom" || this.type === "both") {
            missingGroomForeign = foreignDocs.some(doc => !this.form.groom.documents[doc]);
          }
          
          if (this.type === "bride" || this.type === "both") {
            missingBrideForeign = foreignDocs.some(doc => !this.form.bride.documents[doc]);
          }

          if (missingGroomForeign || missingBrideForeign) {
            this.message.push("Foreigner documents (Legal Capacity, Passport, Decree) are required.");
            this.scrollToError();
            return;
          }
          break;

        case 6: // Age-Based Documents (Consent/Advice)
          let missingGroomAge = false;
          let missingBrideAge = false;

          // Validation for Groom based on his specific age selection
          if (this.groomRequirement === "parental-consent") {
            if (!this.form.groom.documents.parentalConsent) missingGroomAge = true;
          } else if (this.groomRequirement === "parental-advise") {
            if (!this.form.groom.documents.parentalAdvise) missingGroomAge = true;
          }

          // Validation for Bride based on her specific age selection
          if (this.brideRequirement === "parental-consent") {
            if (!this.form.bride.documents.parentalConsent) missingBrideAge = true;
          } else if (this.brideRequirement === "parental-advise") {
            if (!this.form.bride.documents.parentalAdvise) missingBrideAge = true;
          }

          if (missingGroomAge || missingBrideAge) {
            this.message.push("Please upload the required Parental Consent or Advise documents.");
            this.scrollToError();
            return;
          }
          break;

        case 7: // Groom Name
          if (!this.form.groom.firstName || !this.form.groom.lastName) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          // if (!this.form.groom.middleName) this.form.groom.middleName = "N/A";
          break;

        case 8: // Groom Birth Date
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
          const groomAgeRangeError = this.getAgeRangeValidationError(this.form.groom.age, this.groomRequirement, 'groom');
          if (groomAgeRangeError) {
            this.message.push(groomAgeRangeError);
            this.scrollToError();
            return;
          }
          break;

        case 9: // Groom Birth Place
          if (!this.form.groom.cityMunicipality || !this.form.groom.province || !this.form.groom.country) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 10: // Groom Personal Info
          if (!this.form.groom.age || !this.form.groom.sex || !this.form.groom.citizenship) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 11: // Groom Residence
          if (!this.form.groom.residence) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 12: // Groom Religion
          if (!this.form.groom.religion) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 13: // Groom Civil Status
          if (!this.form.groom.civilStatus) {
            this.message.push("Please fill in Civil Status.");
            this.scrollToError();
            return;
          }
          // if (!this.form.groom.previousMarriageDissolve) this.form.groom.previousMarriageDissolve = "N/A";
          // if (!this.form.groom.dissolvedCityMunicipality) this.form.groom.dissolvedCityMunicipality = "N/A";
          // if (!this.form.groom.dissolvedProvince) this.form.groom.dissolvedProvince = "N/A";
          // if (!this.form.groom.dissolvedCountry) this.form.groom.dissolvedCountry = "N/A";
          // if (!this.form.groom.dissolvedDay) this.form.groom.dissolvedDay = "N/A";
          // if (!this.form.groom.dissolvedMonth) this.form.groom.dissolvedMonth = "N/A";
          // if (!this.form.groom.dissolvedYear) this.form.groom.dissolvedYear = "N/A";
          break;

        case 14: // Groom Degree
          // if (!this.form.groom.degree) this.form.groom.degree = "N/A";
          break;

        case 15: // Groom Father Info
          if (!this.form.groom.fatherFirstName || !this.form.groom.fatherLastName || !this.form.groom.fatherCitizenship) {
            this.message.push("Please fill in all required fields excluding Middle Name.");
            this.scrollToError();
            return;
          }
          // if (!this.form.groom.fatherMiddleName) this.form.groom.fatherMiddleName = "N/A";
          break;

        case 16: // Groom Father Residence
          if (!this.form.groom.fatherResidence) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 17: // Groom Mother Info
          if (!this.form.groom.motherMaidenFirstName || !this.form.groom.motherMaidenLastName || !this.form.groom.motherMaidenCitizenship) {
            this.message.push("Please fill in all required fields excluding Middle Name.");
            this.scrollToError();
            return;
          }
          // if (!this.form.groom.motherMaidenMiddleName) this.form.groom.motherMaidenMiddleName = "N/A";
          break;

        case 18: // Groom Mother Residence
          if (!this.form.groom.motherMaidenResidence) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 19: // Groom consent/advice source info
          if (this.groomRequirement !== "no-need") {
            if (!this.form.consentSource.groom.citizenship || !this.form.consentSource.groom.relationship) {
              this.message.push("Please fill in groom consent/advice source citizenship and relationship.");
              this.scrollToError();
              return;
            }
          }
          break;

        case 20: // Groom consent/advice source residence
          if (this.groomRequirement !== "no-need" && !this.form.consentSource.groom.residence) {
            this.message.push("Please fill in groom consent/advice source residence.");
            this.scrollToError();
            return;
          }
          break;

        /* --- BRIDE VALIDATION --- */
        case 22: // Bride Name
          if (!this.form.bride.firstName || !this.form.bride.lastName) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          // if (!this.form.bride.middleName) this.form.bride.middleName = "N/A";
          break;

        case 23: // Bride Birth Date
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
          const brideAgeRangeError = this.getAgeRangeValidationError(this.form.bride.age, this.brideRequirement, 'bride');
          if (brideAgeRangeError) {
            this.message.push(brideAgeRangeError);
            this.scrollToError();
            return;
          }
          break;

        case 24: // Bride Birth Place
          if (!this.form.bride.cityMunicipality || !this.form.bride.province || !this.form.bride.country) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 25: // Bride Personal Info
          if (!this.form.bride.age || !this.form.bride.sex || !this.form.bride.citizenship) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 26: // Bride Residence
          if (!this.form.bride.residence) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 27: // Bride Religion
          if (!this.form.bride.religion) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 28: // Bride Civil Status
          if (!this.form.bride.civilStatus) {
            this.message.push("Please fill in Civil Status.");
            this.scrollToError();
            return;
          }
          // if (!this.form.bride.previousMarriageDissolve) this.form.bride.previousMarriageDissolve = "N/A";
          // if (!this.form.bride.dissolvedCityMunicipality) this.form.bride.dissolvedCityMunicipality = "N/A";
          // if (!this.form.bride.dissolvedProvince) this.form.bride.dissolvedProvince = "N/A";
          // if (!this.form.bride.dissolvedCountry) this.form.bride.dissolvedCountry = "N/A";
          // if (!this.form.bride.dissolvedDay) this.form.bride.dissolvedDay = "N/A";
          // if (!this.form.bride.dissolvedMonth) this.form.bride.dissolvedMonth = "N/A";
          // if (!this.form.bride.dissolvedYear) this.form.bride.dissolvedYear = "N/A";
          break;

        case 29: // Bride Degree
          // if (!this.form.bride.degree) this.form.bride.degree = "N/A";
          break;

        case 30: // Bride Father Info
          if (!this.form.bride.fatherFirstName || !this.form.bride.fatherLastName || !this.form.bride.fatherCitizenship) {
            this.message.push("Please fill in all required fields excluding Middle Name.");
            this.scrollToError();
            return;
          }
          // if (!this.form.bride.fatherMiddleName) this.form.bride.fatherMiddleName = "N/A";
          break;

        case 31: // Bride Father Residence
          if (!this.form.bride.fatherResidence) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 32: // Bride Mother Info
          if (!this.form.bride.motherMaidenFirstName || !this.form.bride.motherMaidenLastName || !this.form.bride.motherMaidenCitizenship) {
            this.message.push("Please fill in all required fields excluding Middle Name.");
            this.scrollToError();
            return;
          }
          // if (!this.form.bride.motherMaidenMiddleName) this.form.bride.motherMaidenMiddleName = "N/A";
          break;

        case 33: // Bride Mother Residence
          if (!this.form.bride.motherMaidenResidence) {
            this.message.push("Please fill in all required fields.");
            this.scrollToError();
            return;
          }
          break;

        case 34: // Bride consent/advice source info
          if (this.brideRequirement !== "no-need") {
            if (!this.form.consentSource.bride.citizenship || !this.form.consentSource.bride.relationship) {
              this.message.push("Please fill in bride consent/advice source citizenship and relationship.");
              this.scrollToError();
              return;
            }
          }
          break;

        case 35: // Bride consent/advice source residence
          if (this.brideRequirement !== "no-need" && !this.form.consentSource.bride.residence) {
            this.message.push("Please fill in bride consent/advice source residence.");
            this.scrollToError();
            return;
          }
          break;

        default:
          break;
      }

      // 2. If there are error messages, stop here!
      if (this.message.length > 0) return;

      // 3. LOGIC FOR SKIPPING: Determine the NEXT step
      let currentStep = this.step;
      let nextStep = currentStep + 1;

      // SKIP Case: Foreigner Documents (Step 5)
      // If not a foreigner, skip step 5 and go straight to step 6
      const foreignerCheck = String(this.isForeigner) === 'true'; // Handles both boolean and string
      if (nextStep === 5 && !foreignerCheck) {
          nextStep++;
      }

      // SKIP Case: Age Documents (Step 6)
      // If neither needs Parental Consent/Advice, skip step 6 and go to step 7
      if (nextStep === 6 && this.brideRequirement === "no-need" && this.groomRequirement === "no-need") {
        nextStep++;
      }

      // If groom does not require consent/advice source, skip steps 19-20.
      if ((nextStep === 19 || nextStep === 20) && this.groomRequirement === "no-need") {
        nextStep = 21;
      }

      // If bride does not require consent/advice source, skip steps 34-35.
      if ((nextStep === 34 || nextStep === 35) && this.brideRequirement === "no-need") {
        nextStep = 36;
      }

      // We only save the text data. Browsers cannot save "File" objects in localStorage.
      const progressData = {
          step: nextStep,
          groom: { ...this.form.groom, documents: {} }, // Strip documents
          bride: { ...this.form.bride, documents: {} }, // Strip documents
          consentSource: { ...this.form.consentSource },
          isForeigner: this.isForeigner,  
          type: this.type,
          groomRequirement: this.groomRequirement,
          brideRequirement: this.brideRequirement
      };

      localStorage.setItem('marriage_form_progress', JSON.stringify(progressData));

      // 4. Finalize the move
      this.step = nextStep;
      window.scrollTo(0, 0);
    },  
    handlePersonValidation(person, type) {
      const today = new Date();
      const currentYear = today.getFullYear();
      const selectedRequirement = type === 'groom' ? this.groomRequirement : this.brideRequirement;
      const { min: minAllowedAge, max: maxAllowedAge } = this.getAgeLimits(selectedRequirement);
      
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
          const minAgeYear = currentYear - minAllowedAge;
          const maxAgeYear = currentYear - maxAllowedAge;

          // Correct out-of-bounds years
          if (person.year > minAgeYear) {
            person.year = minAgeYear;
            if (selectedRequirement === 'no-need') {
              alert("Selected age bracket is 25+, so year was adjusted to keep age at least 25.");
            } else {
              alert(`Selected age bracket is ${this.getRequirementLabel(selectedRequirement)}, so year was adjusted.`);
            }
          } else if (person.year < maxAgeYear) {
            person.year = maxAgeYear;
            alert(`Selected age bracket is ${this.getRequirementLabel(selectedRequirement)}, so year was adjusted.`);
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

    async submitForm() {

      // 2. Show Final Confirmation
      const finalCheck = await Swal.fire({
        title: 'Final Confirmation',
        text: "Please ensure all uploaded photos are clear. Incomplete or blurry documents will cause delays.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0d6efd',
        confirmButtonText: 'Yes, Submit My Application'
      });

      if (!finalCheck.isConfirmed) return;

      // 3. Start Actual Submission to Laravel
      try {
        Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        const formData = new FormData();
        formData.append('type', this.type);
        formData.append('groomRequirement', this.groomRequirement);
        formData.append('brideRequirement', this.brideRequirement);
        formData.append('groom', JSON.stringify(this.form.groom));
        formData.append('bride', JSON.stringify(this.form.bride));
        formData.append('consentSource', JSON.stringify(this.form.consentSource));

        // Append all documents from the "Bucket" logic we built earlier
        Object.keys(this.form.groom.documents).forEach(key => {
          formData.append(`documents_groom[${key}]`, this.form.groom.documents[key]);
        });
        Object.keys(this.form.bride.documents).forEach(key => {
          formData.append(`documents_bride[${key}]`, this.form.bride.documents[key]);
        });

        const response = await submitMarriageLicenseApplication(formData);

        localStorage.removeItem('marriage_form_progress')

        // 4. Success Message showing the Control Number
        Swal.fire({
          icon: 'success',
          title: 'Application Submitted!',
          html: `
            <div class="p-3 glass-inner text-center">
              <p class="mb-2">Your Control Number is:</p>
              <h2 class="text-primary fw-bold mb-3" style="letter-spacing: 2px;">
                ${response.data.control_number}
              </h2>
              
              <div class="alert alert-warning border-0 shadow-sm py-3 px-2 mb-3">
                <h6 class="fw-bold mb-1">ACTION REQUIRED:</h6>
                <p class="small mb-0">
                  Please <b>SCREENSHOT</b> this screen. Present this at the <b>Abuyog LCRO Office</b> to verify your application.
                </p>
              </div>

              <div class="mt-4 p-2 border-top border-white-50">
                <p class="small mb-2 text-muted">Questions or problems with your application?</p>
                <div class="d-flex align-items-center justify-content-center gap-2">
                  <i class="bi bi-person-badge-fill text-primary"></i>
                  <span class="fw-bold">LCRO Marriage Section Staff</span>
                </div>
                <p class="mb-0 fw-bold text-success">
                  <i class="bi bi-telephone-fill"></i> 0912-345-6789
                </p>
                <p class="x-small text-muted italic">(Available Mon-Fri, 8AM - 5PM)</p>
              </div>
            </div>
          `,
          confirmButtonText: 'I have saved my control number',
          confirmButtonColor: '#198754',
          allowOutsideClick: false
        }).then(() => {
          this.$router.push('/home');
        });

      } catch (error) {
        Swal.fire('Error', 'Something went wrong during submission. Please try again.', 'error');
      }
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
  },
  mounted() {
    // Conditional Cohabitation
    this.isCohabiting = this.$route.params.isCohabiting;
    // For foreigner classification
    this.isForeigner = this.$route.params.isForeigner;
    this.type = this.$route.params.type;

    // 2. AUTO-FILL CITIZENSHIP LOGIC
    if (this.type === 'filipino') {
        this.form.groom.citizenship = 'Filipino';
        this.form.bride.citizenship = 'Filipino';
    } else if (this.type === 'groom') {
        this.form.groom.citizenship = 'Others';
        this.form.bride.citizenship = 'Filipino';
    } else if (this.type === 'bride') {
        this.form.groom.citizenship = 'Filipino';
        this.form.bride.citizenship = 'Others';
    }

    // For ager requirements
    this.groomRequirement = this.$route.params.groomRequirement;
    this.brideRequirement = this.$route.params.brideRequirement;
    // console.log(this.isForeigner)

    const savedData = localStorage.getItem('marriage_form_progress');
    if (savedData) {
        const parsed = JSON.parse(savedData);
        // Only load if the user actually wants to continue
        this.form.groom = { ...this.form.groom, ...parsed.groom };
        this.form.bride = { ...this.form.bride, ...parsed.bride };
        this.form.consentSource = { ...this.form.consentSource, ...parsed.consentSource };
        // this.step = parsed.step;

        Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'info',
        title: 'Progress Restored',
        html: '<small>We saved your text info. <b>Note:</b> You must re-upload your document photos for security reasons.</small>',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        background: '#1e3c72',
        color: '#fff'
      });

    }
  },
  beforeUnmount() {
    if (this.groomCityDebounceTimer) {
      clearTimeout(this.groomCityDebounceTimer);
    }
    if (this.groomCityBlurTimer) {
      clearTimeout(this.groomCityBlurTimer);
    }
    if (this.groomCityAbortController) {
      this.groomCityAbortController.abort();
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

/* Better Label Styling */
small.text-white-50 {
  font-size: 0.7rem;
  letter-spacing: 0.5px;
  text-transform: uppercase;
  font-weight: 700;
  margin-bottom: 2px;
}

/* Scroll area padding fix */
.review-scroll-area {
  border: 1px solid rgba(255, 255, 255, 0.15);
  box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
}

/* Vertical Divider for Desktop */
@media (min-width: 768px) {
  .border-white-10 {
    border-color: rgba(255, 255, 255, 0.1) !important;
  }
}

/* Italic text for secondary info */
.italic {
  font-style: italic;
}

.city-suggestion-menu {
  position: absolute;
  z-index: 30;
  top: calc(100% + 6px);
  left: 0;
  right: 0;
  max-height: 220px;
  overflow-y: auto;
  background: #ffffff;
  border: 1px solid rgba(13, 110, 253, 0.25);
  border-radius: 10px;
  box-shadow: 0 10px 24px rgba(0, 0, 0, 0.2);
}

.city-suggestion-item {
  width: 100%;
  border: 0;
  border-bottom: 1px solid rgba(0, 0, 0, 0.06);
  background: #fff;
  text-align: left;
  padding: 10px 12px;
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.city-suggestion-item:last-child {
  border-bottom: 0;
}

.city-suggestion-item:hover {
  background: rgba(13, 110, 253, 0.08);
}

.city-suggestion-name {
  color: #1f2937;
  font-weight: 600;
  line-height: 1.2;
}

.city-suggestion-context {
  color: #6b7280;
  font-size: 0.78rem;
  line-height: 1.2;
}
</style>
