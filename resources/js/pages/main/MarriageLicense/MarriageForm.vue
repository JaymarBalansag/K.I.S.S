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

                <div v-if="step > 6" class="alert alert-light bg-white bg-opacity-10 border-0 mb-4 py-2">
                  <small class="text-white-50"><i class="bi bi-save me-1"></i> Your progress is being automatically saved as you go to next step.</small>
                </div>

                <!-- STEP 1 – Marriage Required Documents -->
                <div v-if="step === 1" class="glass-panel">
                  <div class="alert alert-info bg-primary bg-opacity-10 border-info border-start border-4 mb-4 animate-fade-in">
                    <div class="d-flex align-items-center">
                      <i class="bi bi-camera me-3 fs-4 text-info"></i>
                      <div>
                        <h6 class="mb-0 fw-bold text-white">Document Quality Tip:</h6>
                        <p class="small mb-0 text-white opacity-75">Please ensure photos are <b>not blurred</b>, well-lit, and all text is readable. Blurry documents will cause application delays.</p>
                      </div>
                    </div>
                  </div>
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

                    </div>
                  </div>
                </div>

                <!-- STEP 2 – Marriage Required Documents -->
                <div v-if="step === 2" class="glass-panel">
                  <div class="alert alert-info bg-primary bg-opacity-10 border-info border-start border-4 mb-4 animate-fade-in">
                    <div class="d-flex align-items-center">
                      <i class="bi bi-camera me-3 fs-4 text-info"></i>
                      <div>
                        <h6 class="mb-0 fw-bold text-white">Document Quality Tip:</h6>
                        <p class="small mb-0 text-white opacity-75">Please ensure photos are <b>not blurred</b>, well-lit, and all text is readable. Blurry documents will cause application delays.</p>
                      </div>
                    </div>
                  </div>
                  <h4 class="section-title text-white mb-4">Required Documents</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>  
                  <div class="row g-4">
                    <!-- For Groom -->
                    <div class="col-md-6 border-end border-white-10">
                      <h5 class="text-white small fw-bold text-uppercase mb-3"><i class="bi bi-gender-male"></i> Groom's Documents</h5>
                      

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

                    </div>

                    <!-- For Bride -->
                    <div class="col-md-6">
                      <h5 class="text-white small fw-bold text-uppercase mb-3"><i class="bi bi-gender-female"></i> Bride's Documents</h5>

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

                    </div>
                  </div>
                </div>

                <!-- STEP 3 – Marriage Required Documents -->
                <div v-if="step === 3" class="glass-panel">
                  <div class="alert alert-info bg-primary bg-opacity-10 border-info border-start border-4 mb-4 animate-fade-in">
                    <div class="d-flex align-items-center">
                      <i class="bi bi-camera me-3 fs-4 text-info"></i>
                      <div>
                        <h6 class="mb-0 fw-bold text-white">Document Quality Tip:</h6>
                        <p class="small mb-0 text-white opacity-75">Please ensure photos are <b>not blurred</b>, well-lit, and all text is readable. Blurry documents will cause application delays.</p>
                      </div>
                    </div>
                  </div>
                  <h4 class="section-title text-white mb-4">Required Documents</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>  
                  <div class="row g-4">
                    <!-- For Groom -->
                    <div class="col-md-6 border-end border-white-10">
                      <h5 class="text-white small fw-bold text-uppercase mb-3"><i class="bi bi-gender-male"></i> Groom's Documents</h5>

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

                    </div>

                    <!-- For Bride -->
                    <div class="col-md-6">
                      <h5 class="text-white small fw-bold text-uppercase mb-3"><i class="bi bi-gender-female"></i> Bride's Documents</h5>

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

                    </div>
                  </div>
                </div>

                <!-- STEP 4 – Marriage Required Documents -->
                <div v-if="step === 4" class="glass-panel">
                  <div class="alert alert-info bg-primary bg-opacity-10 border-info border-start border-4 mb-4 animate-fade-in">
                    <div class="d-flex align-items-center">
                      <i class="bi bi-camera me-3 fs-4 text-info"></i>
                      <div>
                        <h6 class="mb-0 fw-bold text-white">Document Quality Tip:</h6>
                        <p class="small mb-0 text-white opacity-75">Please ensure photos are <b>not blurred</b>, well-lit, and all text is readable. Blurry documents will cause application delays.</p>
                      </div>
                    </div>
                  </div>
                  <h4 class="section-title text-white mb-4">Required Documents</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>  
                  <div class="row g-4">
                    <!-- For Groom -->
                    <div class="col-md-6 border-end border-white-10">
                      <h5 class="text-white small fw-bold text-uppercase mb-3"><i class="bi bi-gender-male"></i> Groom's Documents</h5>

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

                <!-- STEP 5 – Foreigner Required Documents  -->
                <div v-if="step === 5 && this.type !== 'filipino'" class="glass-panel">
                  <div class="alert alert-info bg-primary bg-opacity-10 border-info border-start border-4 mb-4 animate-fade-in">
                    <div class="d-flex align-items-center">
                      <i class="bi bi-camera me-3 fs-4 text-info"></i>
                      <div>
                        <h6 class="mb-0 fw-bold text-white">Document Quality Tip:</h6>
                        <p class="small mb-0 text-white opacity-75">Please ensure photos are <b>not blurred</b>, well-lit, and all text is readable. Blurry documents will cause application delays.</p>
                      </div>
                    </div>
                  </div>
                  <h4 class="section-title text-white mb-4">Foreigner Required Documents</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>  
                  <div class="row g-4">
                    <!-- For Groom -->
                    <div v-if="this.type === 'groom' || this.type === 'both' " :class="this.type === 'groom' ? 'col-12' : 'col-md-6'" class="border-end border-white-10 " > 
                      <h5 class="text-white small fw-bold text-uppercase mb-3"><i class="bi bi-gender-male"></i> Groom's Documents</h5>

                      <div class="mb-4">
                        <label class="form-label fw-semibold small text-uppercase text-white-50">Certification of Legal Capacity to Contract<span class="text-info">*</span></label>
                        <div class="glass-upload-container position-relative">
                          <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'groom', 'legalCapacity')" accept="image/*,.pdf" />
                          <div class="glass-placeholder border-glass rounded-3 p-3 text-center">
                            <div v-if="!previews.groom.legalCapacity">
                              <i class="bi bi-cloud-arrow-up fs-3 text-white"></i>
                              <p class="mb-0 smallest text-white">Upload Groom Certificate of Legal Capacity to Contract</p>
                            </div>
                            <div v-else class="animate-fade-in">
                              <img v-if="isImage('groom', 'legalCapacity')" :src="previews.groom.legalCapacity" class="img-fluid rounded-2 glass-preview-img-sm" />
                              <div v-else class="py-2"><i class="bi bi-file-earmark-pdf fs-2 text-info"></i></div>
                              <div class="glass-badge-sm">Selected</div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="mb-4">
                        <label class="form-label fw-semibold small text-uppercase text-white-50">Valid passport / BI Clearance Cert / ACR i-card<span class="text-info">*</span></label>
                        <div class="glass-upload-container position-relative">
                          <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'groom', 'validPassport')" accept="image/*,.pdf" />
                          <div class="glass-placeholder border-glass rounded-3 p-3 text-center">
                            <div v-if="!previews.groom.validPassport">
                              <i class="bi bi-cloud-arrow-up fs-3 text-white"></i>
                              <p class="mb-0 smallest text-white">Upload Valid passport / BI Clearance Cert / ACR i-card</p>
                            </div>
                            <div v-else class="animate-fade-in">
                              <img v-if="isImage('groom', 'validPassport')" :src="previews.groom.validPassport" class="img-fluid rounded-2 glass-preview-img-sm" />
                              <div v-else class="py-2"><i class="bi bi-file-earmark-pdf fs-2 text-info"></i></div>
                              <div class="glass-badge-sm">Selected</div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="mb-4">
                        <label class="form-label fw-semibold small text-uppercase text-white-50">Apostilled Foreign Death Cert of dead spouse / Divorce Decree, if previously married<span class="text-info">*</span></label>
                        <div class="glass-upload-container position-relative">
                          <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'groom', 'apostilledOrDecree')" accept="image/*,.pdf" />
                          <div class="glass-placeholder border-glass rounded-3 p-3 text-center">
                            <div v-if="!previews.groom.apostilledOrDecree">
                              <i class="bi bi-cloud-arrow-up fs-3 text-white"></i>
                              <p class="mb-0 smallest text-white">Upload Apostilled Foreign Death Cert of dead spouse / Divorce Decree, if previously married</p>
                            </div>
                            <div v-else class="animate-fade-in">
                              <img v-if="isImage('groom', 'apostilledOrDecree')" :src="previews.groom.apostilledOrDecree" class="img-fluid rounded-2 glass-preview-img-sm" />
                              <div v-else class="py-2"><i class="bi bi-file-earmark-pdf fs-2 text-info"></i></div>
                              <div class="glass-badge-sm">Selected</div>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>

                    <!-- For Bride -->
                    <div v-if="this.type === 'bride' || this.type === 'both'" :class="this.type === 'bride' ? 'col-12' : 'col-md-6'">
                      <h5 class="text-white small fw-bold text-uppercase mb-3"><i class="bi bi-gender-female"></i>Foreigner Bride's Documents</h5>

                      <div class="mb-4">
                        <label class="form-label fw-semibold small text-uppercase text-white-50">Certification of Legal Capacity to Contract<span class="text-info">*</span></label>
                        <div class="glass-upload-container position-relative">
                          <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'bride', 'legalCapacity')" accept="image/*,.pdf" />
                          <div class="glass-placeholder border-glass rounded-3 p-3 text-center">
                            <div v-if="!previews.bride.legalCapacity">
                              <i class="bi bi-cloud-arrow-up fs-3 text-white"></i>
                              <p class="mb-0 smallest text-white">Upload Bride Certificate of Legal Capacity to Contract</p>
                            </div>
                            <div v-else class="animate-fade-in">
                              <img v-if="isImage('bride', 'legalCapacity')" :src="previews.bride.legalCapacity" class="img-fluid rounded-2 glass-preview-img-sm" />
                              <div v-else class="py-2"><i class="bi bi-file-earmark-pdf fs-2 text-info"></i></div>
                              <div class="glass-badge-sm">Selected</div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="mb-4">
                        <label class="form-label fw-semibold small text-uppercase text-white-50">Valid passport / BI Clearance Cert / ACR i-card<span class="text-info">*</span></label>
                        <div class="glass-upload-container position-relative">
                          <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'bride', 'validPassport')" accept="image/*,.pdf" />
                          <div class="glass-placeholder border-glass rounded-3 p-3 text-center">
                            <div v-if="!previews.bride.validPassport">
                              <i class="bi bi-cloud-arrow-up fs-3 text-white"></i>
                              <p class="mb-0 smallest text-white">Upload Valid passport / BI Clearance Cert / ACR i-card</p>
                            </div>
                            <div v-else class="animate-fade-in">
                              <img v-if="isImage('bride', 'validPassport')" :src="previews.bride.validPassport" class="img-fluid rounded-2 glass-preview-img-sm" />
                              <div v-else class="py-2"><i class="bi bi-file-earmark-pdf fs-2 text-info"></i></div>
                              <div class="glass-badge-sm">Selected</div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="mb-4">
                        <label class="form-label fw-semibold small text-uppercase text-white-50">Apostilled Foreign Death Cert of dead spouse / Divorce Decree, if previously married<span class="text-info">*</span></label>
                        <div class="glass-upload-container position-relative">
                          <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'bride', 'apostilledOrDecree')" accept="image/*,.pdf" />
                          <div class="glass-placeholder border-glass rounded-3 p-3 text-center">
                            <div v-if="!previews.bride.apostilledOrDecree">
                              <i class="bi bi-cloud-arrow-up fs-3 text-white"></i>
                              <p class="mb-0 smallest text-white">Upload Apostilled Foreign Death Cert of dead spouse / Divorce Decree, if previously married</p>
                            </div>
                            <div v-else class="animate-fade-in">
                              <img v-if="isImage('bride', 'apostilledOrDecree')" :src="previews.bride.apostilledOrDecree" class="img-fluid rounded-2 glass-preview-img-sm" />
                              <div v-else class="py-2"><i class="bi bi-file-earmark-pdf fs-2 text-info"></i></div>
                              <div class="glass-badge-sm">Selected</div>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>

                <!-- STEP 6 – Age Required Documents -->
                <div v-if="step === 6 && (this.groomRequirement !== 'no-need' || this.brideRequirement !== 'no-need')" class="glass-panel">
                  <div class="alert alert-info bg-primary bg-opacity-10 border-info border-start border-4 mb-4 animate-fade-in">
                    <div class="d-flex align-items-center">
                      <i class="bi bi-camera me-3 fs-4 text-info"></i>
                      <div>
                        <h6 class="mb-0 fw-bold text-white">Document Quality Tip:</h6>
                        <p class="small mb-0 text-white opacity-75">Please ensure photos are <b>not blurred</b>, well-lit, and all text is readable. Blurry documents will cause application delays.</p>
                      </div>
                    </div>
                  </div>
                  <h4 class="section-title text-white mb-4">Age Required Documents</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>  
                  <div class="row g-4">
                    <!-- For Groom -->
                    <div class="col-md-6 border-end border-white-10">
                      <h5 class="text-white small fw-bold text-uppercase mb-3"><i class="bi bi-gender-male"></i> Groom's Documents</h5>

                      <div class="mb-4" v-if="this.groomRequirement === 'parental-consent'">
                        <label class="form-label fw-semibold small text-uppercase text-white-50">Parental Consent <span class="text-info">*</span></label>
                        <div class="glass-upload-container position-relative">
                          <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'groom', 'parentalConsent')" accept="image/*,.pdf" />
                          <div class="glass-placeholder border-glass rounded-3 p-3 text-center">
                            <div v-if="!previews.groom.parentalConsent">
                              <i class="bi bi-cloud-arrow-up fs-3 text-white"></i>
                              <p class="mb-0 smallest text-white">Upload Parental Consent</p>
                            </div>
                            <div v-else class="animate-fade-in">
                              <img v-if="isImage('groom', 'parentalConsent')" :src="previews.groom.parentalConsent" class="img-fluid rounded-2 glass-preview-img-sm" />
                              <div v-else class="py-2"><i class="bi bi-file-earmark-pdf fs-2 text-info"></i></div>
                              <div class="glass-badge-sm">Selected</div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="mb-4" v-if="this.groomRequirement === 'parental-advise'">
                        <label class="form-label fw-semibold small text-uppercase text-white-50">Parental Advise <span class="text-info">*</span></label>
                        <div class="glass-upload-container position-relative">
                          <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'groom', 'parentalAdvise')" accept="image/*,.pdf" />
                          <div class="glass-placeholder border-glass rounded-3 p-3 text-center">
                            <div v-if="!previews.groom.parentalAdvise">
                              <i class="bi bi-cloud-arrow-up fs-3 text-white"></i>
                              <p class="mb-0 smallest text-white">Upload Parental Advise</p>
                            </div>
                            <div v-else class="animate-fade-in">
                              <img v-if="isImage('groom', 'parentalAdvise')" :src="previews.groom.parentalAdvise" class="img-fluid rounded-2 glass-preview-img-sm" />
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

                      <div class="mb-4" v-if="this.brideRequirement === 'parental-consent'">
                        <label class="form-label fw-semibold small text-uppercase text-white-50">Parental Consent <span class="text-info">*</span></label>
                        <div class="glass-upload-container position-relative">
                          <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'bride', 'parentalConsent')" accept="image/*,.pdf" />
                          <div class="glass-placeholder border-glass rounded-3 p-3 text-center">
                            <div v-if="!previews.bride.parentalConsent">
                              <i class="bi bi-cloud-arrow-up fs-3 text-white"></i>
                              <p class="mb-0 smallest text-white">Upload Parental Consent</p>
                            </div>
                            <div v-else class="animate-fade-in">
                              <img v-if="isImage('bride', 'parentalConsent')" :src="previews.bride.parentalConsent" class="img-fluid rounded-2 glass-preview-img-sm" />
                              <div v-else class="py-2"><i class="bi bi-file-earmark-pdf fs-2 text-info"></i></div>
                              <div class="glass-badge-sm">Selected</div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="mb-4" v-if="this.brideRequirement === 'parental-advise'">
                        <label class="form-label fw-semibold small text-uppercase text-white-50">Parental Advise <span class="text-info">*</span></label>
                        <div class="glass-upload-container position-relative">
                          <input type="file" class="file-input-overlay" @change="handleFileUpload($event, 'bride', 'parentalAdvise')" accept="image/*,.pdf" />
                          <div class="glass-placeholder border-glass rounded-3 p-3 text-center">
                            <div v-if="!previews.bride.parentalAdvise">
                              <i class="bi bi-cloud-arrow-up fs-3 text-white"></i>
                              <p class="mb-0 smallest text-white">Upload Parental Advise</p>
                            </div>
                            <div v-else class="animate-fade-in">
                              <img v-if="isImage('bride', 'parentalAdvise')" :src="previews.bride.parentalAdvise" class="img-fluid rounded-2 glass-preview-img-sm" />
                              <div v-else class="py-2"><i class="bi bi-file-earmark-pdf fs-2 text-info"></i></div>
                              <div class="glass-badge-sm">Selected</div>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>

                <!-- STEP 7 – GROOM NAME -->
                <div v-if="step === 7">
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

                <!-- STEP 8 – GROOM BIRTH DATE -->
                <div v-if="step === 8">
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

                <!-- STEP 9 – GROOM PLACE OF BIRTH -->
                <div v-if="step === 9">
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

                <!-- STEP 10 – GROOM INFO -->
                <div v-if="step === 10">
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
                      <input class="form-control bg-light" v-model="form.groom.sex" readonly placeholder="Auto-calculated">
                    </div>

                    <div class="col-md-4">
                      <label class="form-label">Citizenship</label>
                      <select class="form-control" v-model="form.groom.citizenship">
                        <option value="" disabled>Select Citizenship</option>
                        <option value="Filipino">Filipino</option>
                        <option value="Foreign National">Foreign National</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- STEP 11 – GROOM RESIDENCE -->
                <div v-if="step === 11">
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

                <!-- STEP 12 – GROOM RELIGION -->
                <div v-if="step === 12">
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
                
                <!-- STEP 13 – GROOM CIVIL STATUS -->
                <div v-if="step === 13">
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

                <!-- STEP 14 – GROOM DEGREE -->
                <div v-if="step === 14">
                  <h4 class="section-title">Groom - Degree of relationship contracting parties</h4>
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label">Degree</label>
                      <input class="form-control" v-model="form.groom.degree">
                      <small class=" text-primary">Tips: you can leave this blank if not applicable.</small>
                    </div>
                  </div>
                </div>

                <!-- STEP 15 – GROOM FATHER'S INFO -->
                <div v-if="step === 15">
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
                        <option value="Foreign National">Foreign National</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- STEP 16 – GROOM FATHER'S RESIDENCE -->
                <div v-if="step === 16">
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

                <!-- STEP 17 – GROOM MOTHER'S MAIDEN INFO -->
                <div v-if="step === 17">
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
                        <option value="Foreign National">Foreign National</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- STEP 18 – GROOM MOTHER'S MAIDEN RESIDENCE -->
                <div v-if="step === 18">
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

                <!-- STEP 19 – GROOM CONSENT/ADVICE SOURCE INFO -->
                <div v-if="step === 19 && groomRequirement !== 'no-need'">
                  <h4 class="section-title">Groom - Person Who Gave {{ groomRequirement === 'parental-consent' ? 'Consent' : 'Advice' }} (Info)</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-4">
                      <label class="form-label">First Name</label>
                      <input class="form-control" v-model="form.consentSource.groom.firstName">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Middle Name</label>
                      <input class="form-control" v-model="form.consentSource.groom.middleName">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Last Name</label>
                      <input class="form-control" v-model="form.consentSource.groom.lastName">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Citizenship</label>
                      <select class="form-control" v-model="form.consentSource.groom.citizenship">
                        <option value="" disabled>Select Citizenship</option>
                        <option value="Filipino">Filipino</option>
                        <option value="Foreign National">Foreign National</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Relationship</label>
                      <input class="form-control" v-model="form.consentSource.groom.relationship" placeholder="e.g. Father / Mother / Guardian">
                    </div>
                  </div>
                </div>

                <!-- STEP 20 – GROOM CONSENT/ADVICE SOURCE RESIDENCE -->
                <div v-if="step === 20 && groomRequirement !== 'no-need'">
                  <h4 class="section-title">Groom - Person Who Gave {{ groomRequirement === 'parental-consent' ? 'Consent' : 'Advice' }} (Residence)</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label">House No., Street, City/Municipality, Province, Country</label>
                      <input class="form-control" v-model="form.consentSource.groom.residence">
                      <small class=" text-primary">Tips: Enter the full address including house number, street name, city/municipality, province, and country.</small>
                    </div>
                  </div>
                </div>

                <!-- STEP 21 – GROOM REVIEW -->
                <div v-if="step === 21">
                  <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary p-2 rounded-3 me-3">
                      <i class="bi bi-person-check-fill fs-4 text-white"></i>
                    </div>
                    <h4 class="section-title mb-0 border-0 p-0">Review Groom's Information</h4>
                  </div>

                  <div class="review-scroll-area glass-inner p-4">
                    <div class="row g-4">
                      <div class="col-12">
                        <h6 class="text-info small fw-bold text-uppercase mb-3" style="letter-spacing: 1px;">
                          <i class="bi bi-info-circle me-2"></i>Personal Details
                        </h6>
                        <div class="row row-cols-1 row-cols-md-2 g-3">
                          <div class="col"><small class="text-white-50 d-block">Full Name</small><span class="text-white fw-bold">{{ form.groom.firstName }} {{ form.groom.middleName }} {{ form.groom.lastName }}</span></div>
                          <div class="col"><small class="text-white-50 d-block">Birth Date</small><span class="text-white">{{ form.groom.month }}/{{ form.groom.day }}/{{ form.groom.year }} (Age: {{ form.groom.age }})</span></div>
                          <div class="col"><small class="text-white-50 d-block">Civil Status</small><span class="badge bg-primary glow-badge mt-1">{{ form.groom.civilStatus }}</span></div>
                          <div class="col"><small class="text-white-50 d-block">Citizenship | Religion</small><span class="text-white">{{ form.groom.citizenship }} | {{ form.groom.religion }}</span></div>
                          <div class="col-12"><small class="text-white-50 d-block">Residence</small><span class="text-white small">{{ form.groom.residence }}</span></div>
                        </div>
                      </div>

                      <div class="col-12 border-top border-white-10 pt-4">
                        <h6 class="text-info small fw-bold text-uppercase mb-3" style="letter-spacing: 1px;">
                          <i class="bi bi-people me-2"></i>Family Background
                        </h6>
                        <div class="row g-3">
                          <div class="col-md-6 border-end border-white-10">
                            <small class="text-white-50 d-block">Father's Name</small>
                            <span class="text-white d-block">{{ form.groom.fatherFirstName }} {{ form.groom.fatherLastName }}</span>
                            <span class="smallest text-white-50 italic">({{ form.groom.fatherCitizenship }})</span>
                          </div>
                          <div class="col-md-6">
                            <small class="text-white-50 d-block">Mother's Maiden Name</small>
                            <span class="text-white d-block">{{ form.groom.motherMaidenFirstName }} {{ form.groom.motherMaidenLastName }}</span>
                            <span class="smallest text-white-50 italic">({{ form.groom.motherMaidenCitizenship }})</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- STEP 22 – BRIDE NAME -->
                <div v-if="step === 22">
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

                <!-- STEP 23 – BRIDE BIRTH DATE -->
                <div v-if="step === 23">
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

                <!-- STEP 24 – BRIDE PLACE OF BIRTH -->
                <div v-if="step === 24">
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

                <!-- STEP 25 – BRIDE INFO -->
                <div v-if="step === 25">
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
                      <input class="form-control bg-light" v-model="form.bride.sex" readonly placeholder="Auto-calculated">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Citizenship</label>
                      <select class="form-control" v-model="form.bride.citizenship">
                        <option value="" disabled>Select Citizenship</option>
                        <option value="Filipino">Filipino</option>
                        <option value="Foreign National">Foreign National</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- STEP 26 – BRIDE RESIDENCE -->
                <div v-if="step === 26">
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

                <!-- STEP 27 – BRIDE RELIGION -->
                <div v-if="step === 27">
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
                
                <!-- STEP 28 – BRIDE CIVIL STATUS -->
                <div v-if="step === 28">
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

                <!-- STEP 29 – BRIDE DEGREE -->
                <div v-if="step === 29">
                  <h4 class="section-title">Brides - Degree of relationship contracting parties</h4>
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label">Degree</label>
                      <input class="form-control" v-model="form.bride.degree">
                      <small class=" text-primary">Tips: you can leave this blank if not applicable.</small>
                    </div>
                  </div>
                </div>

                <!-- STEP 30 – BRIDE FATHER'S INFO -->
                <div v-if="step === 30">
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
                        <option value="Foreign National">Foreign National</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- STEP 31 – BRIDE FATHER'S RESIDENCE -->
                <div v-if="step === 31">
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

                <!-- STEP 32 – BRIDE MOTHER'S MAIDEN INFO -->
                <div v-if="step === 32">
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
                        <option value="Foreign National">Foreign National</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- STEP 33 – BRIDE MOTHER'S MAIDEN RESIDENCE -->
                <div v-if="step === 33">
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

                <!-- STEP 34 – BRIDE CONSENT/ADVICE SOURCE INFO -->
                <div v-if="step === 34 && brideRequirement !== 'no-need'">
                  <h4 class="section-title">Bride - Person Who Gave {{ brideRequirement === 'parental-consent' ? 'Consent' : 'Advice' }} (Info)</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
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
                        <option value="" disabled>Select Citizenship</option>
                        <option value="Filipino">Filipino</option>
                        <option value="Foreign National">Foreign National</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Relationship</label>
                      <input class="form-control" v-model="form.consentSource.bride.relationship" placeholder="e.g. Father / Mother / Guardian">
                    </div>
                  </div>
                </div>

                <!-- STEP 35 – BRIDE CONSENT/ADVICE SOURCE RESIDENCE -->
                <div v-if="step === 35 && brideRequirement !== 'no-need'">
                  <h4 class="section-title">Bride - Person Who Gave {{ brideRequirement === 'parental-consent' ? 'Consent' : 'Advice' }} (Residence)</h4>
                  <div ref="errorContainer">
                    <WarningForm v-for="(msg, index) in message" :key="index" :message="msg" class="mb-3"/>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label">House No., Street, City/Municipality, Province, Country</label>
                      <input class="form-control" v-model="form.consentSource.bride.residence">
                      <small class=" text-primary">Tips: Enter the full address including house number, street name, city/municipality, province, and country.</small>
                    </div>
                  </div>
                </div>

                <!-- STEP 36 – BRIDE REVIEW -->
                <div v-if="step === 36">
                  <div class="d-flex align-items-center mb-4">
                    <div class="bg-danger p-2 rounded-3 me-3">
                      <i class="bi bi-person-check-fill fs-4 text-white"></i>
                    </div>
                    <h4 class="section-title mb-0 border-0 p-0 text-white">Review Bride's Information</h4>
                  </div>

                  <div class="review-scroll-area glass-inner p-4">
                    <div class="row g-4">
                      <div class="col-12">
                        <h6 class="text-danger-emphasis small fw-bold text-uppercase mb-3" style="letter-spacing: 1px; color: #ff8fa3 !important;">
                          <i class="bi bi-info-circle me-2"></i>Personal Details
                        </h6>
                        <div class="row row-cols-1 row-cols-md-2 g-3">
                          <div class="col"><small class="text-white-50 d-block">Full Name</small><span class="text-white fw-bold">{{ form.bride.firstName }} {{ form.bride.middleName }} {{ form.bride.lastName }}</span></div>
                          <div class="col"><small class="text-white-50 d-block">Birth Date</small><span class="text-white">{{ form.bride.month }}/{{ form.bride.day }}/{{ form.bride.year }} (Age: {{ form.bride.age }})</span></div>
                          <div class="col"><small class="text-white-50 d-block">Civil Status</small><span class="badge bg-danger glow-badge mt-1">{{ form.bride.civilStatus }}</span></div>
                          <div class="col"><small class="text-white-50 d-block">Citizenship | Religion</small><span class="text-white">{{ form.bride.citizenship }} | {{ form.bride.religion }}</span></div>
                          <div class="col-12"><small class="text-white-50 d-block">Residence</small><span class="text-white small">{{ form.bride.residence }}</span></div>
                        </div>
                      </div>

                      <div class="col-12 border-top border-white-10 pt-4">
                        <h6 class="text-danger-emphasis small fw-bold text-uppercase mb-3" style="letter-spacing: 1px; color: #ff8fa3 !important;">
                          <i class="bi bi-people me-2"></i>Family Background
                        </h6>
                        <div class="row g-3">
                          <div class="col-md-6 border-end border-white-10">
                            <small class="text-white-50 d-block">Father's Name</small>
                            <span class="text-white d-block">{{ form.bride.fatherFirstName }} {{ form.bride.fatherLastName }}</span>
                            <span class="smallest text-white-50 italic">({{ form.bride.fatherCitizenship }})</span>
                          </div>
                          <div class="col-md-6">
                            <small class="text-white-50 d-block">Mother's Maiden Name</small>
                            <span class="text-white d-block">{{ form.bride.motherMaidenFirstName }} {{ form.bride.motherMaidenLastName }}</span>
                            <span class="smallest text-white-50 italic">({{ form.bride.motherMaidenCitizenship }})</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- STEP 37 – REVIEW -->
                <div v-if="step === 37">
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

                <!-- NAVIGATION -->
                <div class="d-flex justify-content-between mt-5">
                  <button class="btn btn-outline-light"
                          :disabled="step === 1"
                          @click="goBack()">
                    Back
                  </button>

                  <button v-if="step < totalSteps && step != 37"
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
      step: 1,
      totalSteps: 37,
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
        this.form.groom.citizenship = 'Foreign National';
        this.form.bride.citizenship = 'Filipino';
    } else if (this.type === 'bride') {
        this.form.groom.citizenship = 'Filipino';
        this.form.bride.citizenship = 'Foreign National';
    } else if (this.type === 'both') {
        this.form.groom.citizenship = 'Foreign National';
        this.form.bride.citizenship = 'Foreign National';
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
</style>

