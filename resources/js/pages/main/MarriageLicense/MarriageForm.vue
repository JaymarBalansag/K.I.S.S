<template>
    <div class="main-container min-vh-100 d-flex flex-column">
        <main class="flex-grow-1 py-5 mt-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-9">

                        <div class="text-start mb-4">
                            <a @click.prevent="$router.back()"
                                class="text-white text-decoration-none opacity-75 hover-opacity-100"
                                style="cursor: pointer;">
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

                                <div v-if="step > 1"
                                    class="alert alert-light bg-white bg-opacity-10 border-0 mb-4 py-2">
                                    <small class="text-white-50"><i class="bi bi-save me-1"></i> Your progress is being
                                        automatically saved as you go to next step.</small>
                                </div>

                                <!-- STEP 1 – Marriage Required Documents -->
                                <div v-if="step === 1" class="glass-panel p-4">
                                    <h4 class="section-title text-white mb-4">Required Documents</h4>

                                    <div ref="errorContainer">
                                        <WarningForm v-for="(msg, index) in message" :key="index" :message="msg"
                                            class="mb-3" />
                                    </div>

                                    <div class="document-grid-container">
                                        <div class="row mb-3 d-none d-md-flex">
                                            <div class="col-md-6 text-center"><span
                                                    class="badge bg-primary text-uppercase px-3 py-2">Groom's
                                                    Files</span></div>
                                            <div class="col-md-6 text-center"><span
                                                    class="badge bg-info text-dark text-uppercase px-3 py-2">Bride's
                                                    Files</span></div>
                                        </div>

                                        <div v-for="doc in ['cenomar', 'psa', 'govtIssuedId', 'pmocCertificate']"
                                            :key="doc" class="row g-4 mb-5 border-bottom border-white-10 pb-4">
                                            <div class="col-12">
                                                <label
                                                    class="form-label fw-bold small text-uppercase text-info tracking-wider">
                                                    {{ doc === 'govtIssuedId' ? 'Government Issued ID' : doc ===
                                                        'pmocCertificate' ? 'PMOC Certificate' : doc.toUpperCase() }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                            </div>

                                            <div class="col-md-6 border-md-end border-white-10">
                                                <div class="glass-upload-container position-relative">
                                                    <input type="file" class="file-input-overlay"
                                                        @change="handleFileUpload($event, 'groom', doc)"
                                                        accept="image/*,.pdf" />
                                                    <div class="glass-placeholder border-glass rounded-3 p-3 text-center"
                                                        :class="{ 'has-file': previews.groom[doc] }">
                                                        <div v-if="!previews.groom[doc]">
                                                            <i class="bi bi-cloud-arrow-up fs-3 text-white-50"></i>
                                                            <p class="mb-0 smallest text-white-50">Upload Groom {{
                                                                doc.toUpperCase() }}</p>
                                                        </div>
                                                        <div v-else class="animate-fade-in">
                                                            <img v-if="isImage('groom', doc)" :src="previews.groom[doc]"
                                                                class="img-fluid rounded-2 glass-preview-img-sm" />
                                                            <div v-else class="py-2"><i
                                                                    class="bi bi-file-earmark-pdf fs-2 text-info"></i>
                                                            </div>
                                                            <div class="glass-badge-sm">Selected</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="glass-upload-container position-relative">
                                                    <input type="file" class="file-input-overlay"
                                                        @change="handleFileUpload($event, 'bride', doc)"
                                                        accept="image/*,.pdf" />
                                                    <div class="glass-placeholder border-glass rounded-3 p-3 text-center"
                                                        :class="{ 'has-file': previews.bride[doc] }">
                                                        <div v-if="!previews.bride[doc]">
                                                            <i class="bi bi-cloud-arrow-up fs-3 text-white-50"></i>
                                                            <p class="mb-0 smallest text-white-50">Upload Bride {{
                                                                doc.toUpperCase() }}</p>
                                                        </div>
                                                        <div v-else class="animate-fade-in">
                                                            <img v-if="isImage('bride', doc)" :src="previews.bride[doc]"
                                                                class="img-fluid rounded-2 glass-preview-img-sm" />
                                                            <div v-else class="py-2"><i
                                                                    class="bi bi-file-earmark-pdf fs-2 text-info"></i>
                                                            </div>
                                                            <div class="glass-badge-sm">Selected</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-if="!(brideRequirement == 'no-need' && groomRequirement == 'no-need')"
                                            class="mt-5 animate-fade-in">
                                            <h5 class="text-info small fw-bold text-uppercase mb-4"><i
                                                    class="bi bi-people me-2"></i> Parental Requirements (By Age)</h5>
                                            <div class="row g-4 border-bottom border-white-10 pb-4">

                                                <div
                                                    :class="brideRequirement === 'no-need' ? 'col-12' : 'col-md-6 border-md-end border-white-10'">

                                                    <div v-if="groomRequirement == 'parental-consent'" class="mb-3">
                                                        <label
                                                            class="form-label fw-bold small text-info text-uppercase">Parental
                                                            Consent (Groom)</label>
                                                        <div class="glass-upload-container position-relative">
                                                            <input type="file" class="file-input-overlay"
                                                                @change="handleFileUpload($event, 'groom', 'parentalConsent')"
                                                                accept="image/*,.pdf" />
                                                            <div class="glass-placeholder border-glass rounded-3 p-3 text-center"
                                                                :class="{ 'has-file': previews.groom.parentalConsent }">
                                                                <div v-if="!previews.groom.parentalConsent">
                                                                    <i
                                                                        class="bi bi-file-earmark-arrow-up text-white-50"></i>
                                                                    <p class="smallest text-white-50">Upload Consent</p>
                                                                </div>
                                                                <div v-else class="text-info small">Consent Loaded</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div v-if="groomRequirement == 'parental-advise'" class="mb-3">
                                                        <label
                                                            class="form-label fw-bold small text-info text-uppercase">Parental
                                                            Advice (Groom)</label>
                                                        <div class="glass-upload-container position-relative">
                                                            <input type="file" class="file-input-overlay"
                                                                @change="handleFileUpload($event, 'groom', 'parentalAdvise')"
                                                                accept="image/*,.pdf" />
                                                            <div class="glass-placeholder border-glass rounded-3 p-3 text-center"
                                                                :class="{ 'has-file': previews.groom.parentalAdvise }">
                                                                <div v-if="!previews.groom.parentalAdvise">
                                                                    <i
                                                                        class="bi bi-file-earmark-arrow-up text-white-50"></i>
                                                                    <p class="smallest text-white-50">Upload Advice</p>
                                                                </div>
                                                                <div v-else class="text-info small">Advice Loaded</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div :class="groomRequirement === 'no-need' ? 'col-12' : 'col-md-6'">

                                                    <div v-if="brideRequirement == 'parental-consent'" class="mb-3">
                                                        <label
                                                            class="form-label fw-bold small text-info text-uppercase">Parental
                                                            Consent (Bride)</label>
                                                        <div class="glass-upload-container position-relative">
                                                            <input type="file" class="file-input-overlay"
                                                                @change="handleFileUpload($event, 'bride', 'parentalConsent')"
                                                                accept="image/*,.pdf" />
                                                            <div class="glass-placeholder border-glass rounded-3 p-3 text-center"
                                                                :class="{ 'has-file': previews.bride.parentalConsent }">
                                                                <div v-if="!previews.bride.parentalConsent">
                                                                    <i
                                                                        class="bi bi-file-earmark-arrow-up text-white-50"></i>
                                                                    <p class="smallest text-white-50">Upload Consent</p>
                                                                </div>
                                                                <div v-else class="text-info small">Consent Loaded</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div v-if="brideRequirement == 'parental-advise'" class="mb-3">
                                                        <label
                                                            class="form-label fw-bold small text-info text-uppercase">Parental
                                                            Advice (Bride)</label>
                                                        <div class="glass-upload-container position-relative">
                                                            <input type="file" class="file-input-overlay"
                                                                @change="handleFileUpload($event, 'bride', 'parentalAdvise')"
                                                                accept="image/*,.pdf" />
                                                            <div class="glass-placeholder border-glass rounded-3 p-3 text-center"
                                                                :class="{ 'has-file': previews.bride.parentalAdvise }">
                                                                <div v-if="!previews.bride.parentalAdvise">
                                                                    <i
                                                                        class="bi bi-file-earmark-arrow-up text-white-50"></i>
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
                                            <h5 class="text-warning small fw-bold text-uppercase mb-4"><i
                                                    class="bi bi-globe me-2"></i> Foreigner Requirements</h5>
                                            <div class="row g-4">
                                                <div v-if="type === 'groom' || type === 'both'"
                                                    class="col-md-6 border-md-end border-white-10">
                                                    <div v-for="fDoc in [{ k: 'legalCapacity', l: 'Legal Capacity' }, { k: 'validPassport', l: 'Passport / ACR Card' }]"
                                                        :key="fDoc.k" class="mb-4">
                                                        <label
                                                            class="form-label fw-bold small text-uppercase text-info">{{
                                                                fDoc.l }}</label>
                                                        <div class="glass-upload-container position-relative">
                                                            <input type="file" class="file-input-overlay"
                                                                @change="handleFileUpload($event, 'groom', fDoc.k)"
                                                                accept="image/*,.pdf" />
                                                            <div class="glass-placeholder border-glass rounded-3 p-3 text-center"
                                                                :class="{ 'has-file': previews.groom[fDoc.k] }">
                                                                <div v-if="!previews.groom[fDoc.k]">
                                                                    <i class="bi bi-cloud-arrow-up text-white-50"></i>
                                                                    <p class="mb-0 smallest text-white-50">Upload {{
                                                                        fDoc.l }}</p>
                                                                </div>
                                                                <div v-else class="text-info small">Selected</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-if="type === 'bride' || type === 'both'" class="col-md-6">
                                                    <div v-for="fDoc in [{ k: 'legalCapacity', l: 'Legal Capacity' }, { k: 'validPassport', l: 'Passport / ACR Card' }]"
                                                        :key="fDoc.k" class="mb-4">
                                                        <label
                                                            class="form-label fw-bold small text-uppercase text-info">{{
                                                                fDoc.l }}</label>
                                                        <div class="glass-upload-container position-relative">
                                                            <input type="file" class="file-input-overlay"
                                                                @change="handleFileUpload($event, 'bride', fDoc.k)"
                                                                accept="image/*,.pdf" />
                                                            <div class="glass-placeholder border-glass rounded-3 p-3 text-center"
                                                                :class="{ 'has-file': previews.bride[fDoc.k] }">
                                                                <div v-if="!previews.bride[fDoc.k]">
                                                                    <i class="bi bi-cloud-arrow-up text-white-50"></i>
                                                                    <p class="mb-0 smallest text-white-50">Upload {{
                                                                        fDoc.l }}</p>
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
                                        <WarningForm v-for="(msg, index) in message" :key="index" :message="msg"
                                            class="mb-3" />
                                    </div>

                                    <!-- Name -->
                                    <div class="glass-inner p-4 mb-4">
                                        <h4 class="section-title mb-4">Groom's Primary Information</h4>

                                        <div class="row g-3 mb-4">
                                            <div class="col-md-4">
                                                <label class="form-label">First Name</label>
                                                <input class="form-control" v-model="form.groom.firstName"
                                                    placeholder="Enter first name">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Middle Name</label>
                                                <input class="form-control" v-model="form.groom.middleName"
                                                    placeholder="Enter middle name">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Last Name</label>
                                                <input class="form-control" v-model="form.groom.lastName"
                                                    placeholder="Enter last name">
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Birth Date</label>
                                                <div class="row g-2">
                                                    <div class="col-4">
                                                        <select class="form-control" v-model="form.groom.month">
                                                            <option value="" disabled>Month</option>
                                                            <option v-for="month in monthOptions" :key="month.value"
                                                                :value="month.value">
                                                                {{ month.label }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-4">
                                                        <select class="form-control" v-model="form.groom.day">
                                                            <option value="" disabled>Day</option>
                                                            <option v-for="day in groomDayOptions" :key="`g-day-${day}`"
                                                                :value="day">
                                                                {{ day }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-4">
                                                        <select class="form-control" v-model="form.groom.year">
                                                            <option value="" disabled>Year</option>
                                                            <option v-for="year in groomYearOptions"
                                                                :key="`g-year-${year}`" :value="year">
                                                                {{ year }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <small class="text-info fw-semibold smallest d-block mt-2">
                                                    Allowed age bracket: {{ getRequirementLabel(groomRequirement) }}
                                                </small>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Age</label>
                                                <input class="form-control bg-light text-dark" v-model="form.groom.age"
                                                    readonly placeholder="Auto">
                                                <small v-if="groomAgeError" class="text-danger fw-bold smallest">{{
                                                    groomAgeError }}</small>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Sex</label>
                                                <input class="form-control bg-light text-dark" v-model="form.groom.sex"
                                                    readonly placeholder="Auto">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Origin & Legal Status -->
                                    <div class="glass-inner p-4 mb-4">
                                        <h4 class="section-title mb-4">Origin & Legal Status</h4>

                                        <div class="row g-3 mb-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Birth City / Municipality</label>
                                                <div class="position-relative">
                                                    <input class="form-control" v-model="form.groom.cityMunicipality"
                                                        @input="onGroomCityInput" @focus="onGroomCityFocus"
                                                        @blur="onGroomCityBlur" autocomplete="off"
                                                        placeholder="Type city / municipality">
                                                    <div v-if="showGroomCitySuggestions && groomCitySuggestions.length"
                                                        class="city-suggestion-menu">
                                                        <button v-for="suggestion in groomCitySuggestions"
                                                            :key="suggestion.id" type="button"
                                                            class="city-suggestion-item"
                                                            @mousedown.prevent="selectGroomCitySuggestion(suggestion)">
                                                            <span class="city-suggestion-name">{{
                                                                suggestion.cityMunicipality }}</span>
                                                            <span v-if="suggestion.context"
                                                                class="city-suggestion-context">{{ suggestion.context
                                                                }}</span>
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
                                            <div v-if="form.groom.citizenship === 'Others'"
                                                class="col-md-8 animate-fade-in">
                                                <label class="form-label">Specify Citizenship</label>
                                                <input class="form-control" v-model="form.groom.citizenshipOther"
                                                    list="citizenshipOptions"
                                                    placeholder="Type to search (e.g. American, Canadian...)">
                                                <datalist id="citizenshipOptions">
                                                    <option value="Filipino"></option>
                                                    <option value="American"></option>
                                                    <option value="Canadian"></option>
                                                    <option value="Chinese"></option>
                                                    <option value="Indian"></option>
                                                    <option value="Japanese"></option>
                                                    <option value="Korean"></option>
                                                    <option value="Australian"></option>
                                                    <option value="British"></option>
                                                </datalist>
                                                <datalist id="father-citizenship-options">
                                                    <option value="Filipino"></option>
                                                    <option value="American"></option>
                                                    <option value="Canadian"></option>
                                                    <option value="British"></option>
                                                    <option value="Australian"></option>
                                                    <option value="Japanese"></option>
                                                </datalist>
                                                <datalist id="mother-citizenship-options">
                                                    <option value="Filipino"></option>
                                                    <option value="American"></option>
                                                    <option value="Canadian"></option>
                                                    <option value="British"></option>
                                                    <option value="Korean"></option>
                                                    <option value="Chinese"></option>
                                                </datalist>
                                                <datalist id="source-citizenship-options">
                                                    <option value="Filipino"></option>
                                                    <option value="American"></option>
                                                    <option value="Canadian"></option>
                                                    <option value="British"></option>
                                                    <option value="Australian"></option>
                                                    <option value="Japanese"></option>
                                                </datalist>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Religion / Sect</label>
                                                <input class="form-control" v-model="form.groom.religion"
                                                    list="religionOptions" placeholder="e.g. Roman Catholic">
                                                <datalist id="religionOptions">
                                                    <option value="Christian"></option>
                                                    <option value="Roman Catholic"></option>
                                                    <option value="Islam"></option>
                                                    <option value="Iglesia ni Cristo"></option>
                                                    <option value="Seventh-day Adventist"></option>
                                                    <option value="Jehovah's Witnesses"></option>
                                                    <option value="Latter-day Saints (Mormon)"></option>
                                                    <option value="None / Atheist"></option>
                                                </datalist>
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

                                    <!-- If previously Married -->
                                    <div v-if="isPreviouslyMarried" class="glass-inner p-4 mb-4 border border-info">
                                        <h4 class="section-title mb-4">Dissolution of Previous Marriage</h4>
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label">How was it dissolved?</label>
                                                <textarea class="form-control" rows="2"
                                                    v-model="form.groom.previousMarriageDissolve"></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Place where dissolved</label>
                                                <input class="form-control"
                                                    v-model="form.groom.dissolvedCityMunicipality"
                                                    @input="onGroomDissolvedPlaceInput"
                                                    list="groom-dissolved-place-suggestions" autocomplete="off"
                                                    placeholder="City/Municipality, Province, Country">
                                                <datalist id="groom-dissolved-place-suggestions">
                                                    <option v-for="suggestion in groomDissolvedPlaceSuggestions"
                                                        :key="suggestion.id"
                                                        :value="suggestion.formatted || suggestion.cityMunicipality">
                                                        {{ suggestion.context }}
                                                    </option>
                                                </datalist>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Date when dissolved</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control"
                                                        v-model="form.groom.dissolvedDay"
                                                        list="dissolved-day-suggestions" placeholder="Ex: 24">
                                                    <input type="text" class="form-control"
                                                        v-model="form.groom.dissolvedMonth"
                                                        list="dissolved-month-suggestions" placeholder="Ex: May">
                                                    <input type="text" class="form-control"
                                                        v-model="form.groom.dissolvedYear"
                                                        list="dissolved-year-suggestions" placeholder="Ex: 2004">
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
                                                        <input type="file" class="file-input-overlay"
                                                            @change="handleFileUpload($event, 'groom', 'apostilled')"
                                                            accept="image/*,.pdf" />
                                                        <div class="glass-placeholder border-glass rounded-3 p-3 text-center"
                                                            :class="{ 'has-file': previews.groom.apostilled }">
                                                            <div v-if="!previews.groom.apostilled">
                                                                <i
                                                                    class="bi bi-file-earmark-arrow-up text-white-50"></i>
                                                                <p class="smallest text-white-50">Upload Death
                                                                    Certificate</p>
                                                            </div>
                                                            <div v-else class="text-info small">Death Cert Loaded</div>
                                                        </div>
                                                    </div>

                                                    <div v-if="form.groom.civilStatus === 'Divorced' || form.groom.civilStatus === 'Annulled'"
                                                        key="upload-divorced"
                                                        class="glass-upload-container position-relative">
                                                        <input type="file" class="file-input-overlay"
                                                            @change="handleFileUpload($event, 'groom', 'divorceDecree')"
                                                            accept="image/*,.pdf" />
                                                        <div class="glass-placeholder border-glass rounded-3 p-3 text-center"
                                                            :class="{ 'has-file': previews.groom.divorceDecree }">
                                                            <div v-if="!previews.groom.divorceDecree">
                                                                <i
                                                                    class="bi bi-file-earmark-arrow-up text-white-50"></i>
                                                                <p class="smallest text-white-50">Upload Divorce Decree
                                                                </p>
                                                            </div>
                                                            <div v-else class="text-info small">Divorce Decree Loaded
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Residence & Contact Info -->
                                    <div class="glass-inner p-4 mb-4">
                                        <h4 class="section-title mb-3">Residence & Contact Info</h4>
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label">Full Address</label>
                                                <div class="position-relative">

                                                    <div class="row g-2">


                                                        <div class="col-md-5">
                                                            <input class="form-control"
                                                                v-model="form.groom.streethousenum" autocomplete="off"
                                                                placeholder="House no., Street">
                                                        </div>
                                                        <div class="col-md-7">
                                                            <input class="form-control" v-model="form.groom.residence"
                                                                @input="onGroomResidenceInput"
                                                                @focus="onGroomResidenceFocus"
                                                                @blur="onGroomResidenceBlur" autocomplete="off"
                                                                placeholder="Barangay, City/Municipality, Province, Country, Zip code">
                                                            <div v-if="showGroomResidenceSuggestions && groomResidenceSuggestions.length"
                                                                class="city-suggestion-menu">
                                                                <button v-for="suggestion in groomResidenceSuggestions"
                                                                    :key="suggestion.id" type="button"
                                                                    class="city-suggestion-item"
                                                                    @mousedown.prevent="selectGroomResidenceSuggestion(suggestion)">
                                                                    <span class="city-suggestion-name">{{
                                                                        suggestion.mainText }}</span>
                                                                    <span class="city-suggestion-context">{{
                                                                        suggestion.displayName }}</span>
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </div>


                                                </div>
                                                <small class="text-white-50 smallest mt-2 d-block italic"><i
                                                        class="bi bi-info-circle me-1"></i>Tips: Please enter complete
                                                    details for legal records.</small>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label class="form-label">Degree of relationship (Contracting
                                                    Parties)</label>
                                                <input class="form-control" v-model="form.groom.degree">
                                                <small class="text-white-50 smallest">Leave blank if not
                                                    applicable.</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Parent Information -->
                                    <div class="glass-inner p-4 mb-4">
                                        <h4 class="section-title mb-4">Parents' Information</h4>

                                        <div class="row g-3 mb-4 border-bottom border-white-10 pb-4">
                                            <div class="col-12"><small class="text-white-50">Father's Details</small>
                                            </div>
                                            <div class="col-md-4"><input class="form-control"
                                                    v-model="form.groom.fatherFirstName" placeholder="First Name"></div>
                                            <div class="col-md-4"><input class="form-control"
                                                    v-model="form.groom.fatherMiddleName" placeholder="Middle Name">
                                            </div>
                                            <div class="col-md-4"><input class="form-control"
                                                    v-model="form.groom.fatherLastName" placeholder="Last Name"></div>
                                            <div class="col-md-4">
                                                <select class="form-control" v-model="form.groom.fatherCitizenship">
                                                    <option value="" disabled>Father's Citizenship</option>
                                                    <option value="Filipino">Filipino</option>
                                                    <option value="Others">Others</option>
                                                </select>
                                            </div>
                                            <div v-if="form.groom.fatherCitizenship === 'Others'"
                                                class="col-md-8 animate-fade-in">
                                                <input class="form-control" v-model="form.groom.fatherCitizenshipOther"
                                                    list="father-citizenship-options"
                                                    placeholder="Specify father's citizenship">
                                            </div>

                                            <div class="row g-2">
                                                <label class="form-label">Full Residence</label>
                                                <div class="col-md-5">
                                                    <div class="position-relative">
                                                        <input class="form-control"
                                                            v-model="form.groom.fatherHouseNumStreet" autocomplete="off"
                                                            placeholder="House no., Street">
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="position-relative">
                                                        <input class="form-control" v-model="form.groom.fatherResidence"
                                                            @input="onGroomRelatedResidenceInput('fatherResidence')"
                                                            @focus="onGroomRelatedResidenceFocus('fatherResidence')"
                                                            @blur="onGroomRelatedResidenceBlur" autocomplete="off"
                                                            placeholder="Barangay, City/Municipality, Province, Country, Zip code">
                                                        <div v-if="showGroomRelatedResidenceSuggestions && groomRelatedResidenceField === 'fatherResidence' && groomRelatedResidenceSuggestions.length"
                                                            class="city-suggestion-menu">
                                                            <button
                                                                v-for="suggestion in groomRelatedResidenceSuggestions"
                                                                :key="suggestion.id" type="button"
                                                                class="city-suggestion-item"
                                                                @mousedown.prevent="selectGroomRelatedResidenceSuggestion(suggestion)">
                                                                <span class="city-suggestion-name">{{
                                                                    suggestion.mainText }}</span>
                                                                <span class="city-suggestion-context">{{
                                                                    suggestion.displayName }}</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-12"><small class="text-white-50">Mother's Maiden
                                                    Details</small></div>
                                            <div class="col-md-4"><input class="form-control"
                                                    v-model="form.groom.motherMaidenFirstName" placeholder="First Name">
                                            </div>
                                            <div class="col-md-4"><input class="form-control"
                                                    v-model="form.groom.motherMaidenMiddleName"
                                                    placeholder="Middle Name"></div>
                                            <div class="col-md-4"><input class="form-control"
                                                    v-model="form.groom.motherMaidenLastName" placeholder="Last Name">
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control"
                                                    v-model="form.groom.motherMaidenCitizenship">
                                                    <option value="" disabled>Mother's Citizenship</option>
                                                    <option value="Filipino">Filipino</option>
                                                    <option value="Others">Others</option>
                                                </select>
                                            </div>

                                            <div v-if="form.groom.motherMaidenCitizenship === 'Others'"
                                                class="col-md-8 animate-fade-in">
                                                <input class="form-control"
                                                    v-model="form.groom.motherMaidenCitizenshipOther"
                                                    list="mother-citizenship-options"
                                                    placeholder="Specify mother's citizenship">
                                            </div>

                                            <div class="row g-2">
                                                <label class="form-label">Full Residence</label>
                                                <div class="col-md-5">
                                                    <div class="position-relative">
                                                        <input class="form-control"
                                                            v-model="form.groom.motherHouseNumStreet" autocomplete="off"
                                                            placeholder="House no., Street">
                                                    </div>
                                                </div>

                                                <div class="col-md-7">
                                                    <div class="position-relative">
                                                        <input class="form-control"
                                                            v-model="form.groom.motherMaidenResidence"
                                                            @input="onGroomRelatedResidenceInput('motherMaidenResidence')"
                                                            @focus="onGroomRelatedResidenceFocus('motherMaidenResidence')"
                                                            @blur="onGroomRelatedResidenceBlur" autocomplete="off"
                                                            placeholder="Barangay, City/Municipality, Province, Country, Zip code">
                                                        <div v-if="showGroomRelatedResidenceSuggestions && groomRelatedResidenceField === 'motherMaidenResidence' && groomRelatedResidenceSuggestions.length"
                                                            class="city-suggestion-menu">
                                                            <button
                                                                v-for="suggestion in groomRelatedResidenceSuggestions"
                                                                :key="suggestion.id" type="button"
                                                                class="city-suggestion-item"
                                                                @mousedown.prevent="selectGroomRelatedResidenceSuggestion(suggestion)">
                                                                <span class="city-suggestion-name">{{
                                                                    suggestion.mainText }}</span>
                                                                <span class="city-suggestion-context">{{
                                                                    suggestion.displayName }}</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>


                                        </div>
                                    </div>

                                    <!-- Age Requirements -->
                                    <div v-if="groomRequirement !== 'no-need'"
                                        class="glass-inner p-4 mb-4 border border-warning">
                                        <h4 class="section-title mb-4">Person Providing {{ groomRequirement ===
                                            'parental-consent' ? 'Consent' : 'Advice' }}</h4>
                                        <div class="row g-3">
                                            <div class="col-md-4"><label class="form-label">First Name</label><input
                                                    class="form-control" v-model="form.consentSource.groom.firstName">
                                            </div>
                                            <div class="col-md-4"><label class="form-label">Middle Name</label><input
                                                    class="form-control" v-model="form.consentSource.groom.middleName">
                                            </div>
                                            <div class="col-md-4"><label class="form-label">Last Name</label><input
                                                    class="form-control" v-model="form.consentSource.groom.lastName">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Citizenship</label>
                                                <select class="form-control"
                                                    v-model="form.consentSource.groom.citizenship">
                                                    <option value="" disabled>Select Citizenship</option>
                                                    <option value="Filipino">Filipino</option>
                                                    <option value="Others">Others</option>
                                                </select>
                                            </div>
                                            <div v-if="form.consentSource.groom.citizenship === 'Others'"
                                                class="col-md-8 animate-fade-in">
                                                <label class="form-label">Specify Citizenship</label>
                                                <input class="form-control"
                                                    v-model="form.consentSource.groom.citizenshipOther"
                                                    list="source-citizenship-options" placeholder="Specify citizenship">
                                            </div>
                                            <div class="col-md-4"><label class="form-label">Relationship</label><input
                                                    class="form-control" v-model="form.consentSource.groom.relationship"
                                                    placeholder="e.g. Father"></div>


                                            <div class="row g-2">

                                                <label class="form-label">Full Residence</label>
                                                <div class="col-md-5">
                                                    <div class="position-relative">
                                                        <input class="form-control"
                                                            v-model="form.consentSource.groom.houseNumStreet"
                                                            autocomplete="off" placeholder="House no., Street">
                                                    </div>
                                                </div>

                                                <div class="col-md-7">
                                                    <div class="position-relative">
                                                        <input class="form-control"
                                                            v-model="form.consentSource.groom.residence"
                                                            @input="onGroomRelatedResidenceInput('consentSourceResidence')"
                                                            @focus="onGroomRelatedResidenceFocus('consentSourceResidence')"
                                                            @blur="onGroomRelatedResidenceBlur" autocomplete="off"
                                                            placeholder="Barangay, City/Municipality, Province, Country, Zip code">
                                                        <div v-if="showGroomRelatedResidenceSuggestions && groomRelatedResidenceField === 'consentSourceResidence' && groomRelatedResidenceSuggestions.length"
                                                            class="city-suggestion-menu">
                                                            <button
                                                                v-for="suggestion in groomRelatedResidenceSuggestions"
                                                                :key="suggestion.id" type="button"
                                                                class="city-suggestion-item"
                                                                @mousedown.prevent="selectGroomRelatedResidenceSuggestion(suggestion)">
                                                                <span class="city-suggestion-name">{{
                                                                    suggestion.mainText }}</span>
                                                                <span class="city-suggestion-context">{{
                                                                    suggestion.displayName }}</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>

                                        </div>
                                    </div>

                                </div>

                                <!-- STEP 3 – BRIDE INFO -->
                                <div v-if="step === 3">
                                    <div ref="errorContainer">
                                        <WarningForm v-for="(msg, index) in message" :key="index" :message="msg"
                                            class="mb-3" />
                                    </div>

                                    <div class="glass-inner p-4 mb-4">
                                        <h4 class="section-title mb-4">Bride's Primary Information</h4>

                                        <div class="row g-3 mb-4">
                                            <div class="col-md-4">
                                                <label class="form-label">First Name</label>
                                                <input class="form-control" v-model="form.bride.firstName"
                                                    placeholder="Enter first name">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Middle Name</label>
                                                <input class="form-control" v-model="form.bride.middleName"
                                                    placeholder="Enter middle name">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Last Name</label>
                                                <input class="form-control" v-model="form.bride.lastName"
                                                    placeholder="Enter last name">
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Birth Date</label>
                                                <div class="row g-2">
                                                    <div class="col-4">
                                                        <select class="form-control" v-model="form.bride.month">
                                                            <option value="" disabled>Month</option>
                                                            <option v-for="month in monthOptions"
                                                                :key="`b-month-${month.value}`" :value="month.value">
                                                                {{ month.label }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-4">
                                                        <select class="form-control" v-model="form.bride.day">
                                                            <option value="" disabled>Day</option>
                                                            <option v-for="day in brideDayOptions" :key="`b-day-${day}`"
                                                                :value="day">
                                                                {{ day }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-4">
                                                        <select class="form-control" v-model="form.bride.year">
                                                            <option value="" disabled>Year</option>
                                                            <option v-for="year in brideYearOptions"
                                                                :key="`b-year-${year}`" :value="year">
                                                                {{ year }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <small class="text-info fw-semibold smallest d-block mt-2">
                                                    Allowed age bracket: {{ getRequirementLabel(brideRequirement) }}
                                                </small>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Age</label>
                                                <input class="form-control bg-light text-dark" v-model="form.bride.age"
                                                    readonly placeholder="Auto">
                                                <small v-if="brideAgeError" class="text-danger fw-bold smallest">{{
                                                    brideAgeError }}</small>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Sex</label>
                                                <input class="form-control bg-light text-dark" v-model="form.bride.sex"
                                                    readonly placeholder="Female">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="glass-inner p-4 mb-4">
                                        <h4 class="section-title mb-4">Origin & Legal Status</h4>

                                        <div class="row g-3 mb-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Birth City / Municipality</label>
                                                <div class="position-relative">
                                                    <input class="form-control" v-model="form.bride.cityMunicipality"
                                                        @input="onBrideCityInput" @focus="onBrideCityFocus"
                                                        @blur="onBrideCityBlur" autocomplete="off"
                                                        placeholder="Type city / municipality">
                                                    <div v-if="showBrideCitySuggestions && brideCitySuggestions.length"
                                                        class="city-suggestion-menu">
                                                        <button v-for="suggestion in brideCitySuggestions"
                                                            :key="suggestion.id" type="button"
                                                            class="city-suggestion-item"
                                                            @mousedown.prevent="selectBrideCitySuggestion(suggestion)">
                                                            <span class="city-suggestion-name">{{
                                                                suggestion.cityMunicipality }}</span>
                                                            <span v-if="suggestion.context"
                                                                class="city-suggestion-context">{{ suggestion.context
                                                                }}</span>
                                                        </button>
                                                    </div>
                                                </div>
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
                                                <select class="form-control" disabled v-model="form.bride.citizenship">
                                                    <option value="" disabled>Select Citizenship</option>
                                                    <option value="Filipino">Filipino</option>
                                                    <option value="Others">Others</option>
                                                </select>
                                            </div>
                                            <div v-if="form.bride.citizenship === 'Others'"
                                                class="col-md-8 animate-fade-in">
                                                <label class="form-label">Specify Citizenship</label>
                                                <input class="form-control" v-model="form.bride.citizenshipOther"
                                                    list="citizenshipOptions"
                                                    placeholder="Type to search (e.g. American, Canadian...)">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Religion / Sect</label>
                                                <input class="form-control" v-model="form.bride.religion"
                                                    list="religionOptions" placeholder="e.g. Roman Catholic">
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
                                                <div class="row g-2">
                                                    <div class="col-md-2">
                                                        <input class="form-control" v-model="form.bride.housenum"
                                                            autocomplete="off" placeholder="House no.">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input class="form-control" v-model="form.bride.street"
                                                            autocomplete="off" placeholder="Street.">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="position-relative">
                                                            <input class="form-control" v-model="form.bride.residence"
                                                                @input="onBrideResidenceInput"
                                                                @focus="onBrideResidenceFocus"
                                                                @blur="onBrideResidenceBlur" autocomplete="off"
                                                                placeholder="Barangay, City/Municipality, Province, Country, Zip code">
                                                            <div v-if="showBrideResidenceSuggestions && brideResidenceSuggestions.length"
                                                                class="city-suggestion-menu">
                                                                <button v-for="suggestion in brideResidenceSuggestions"
                                                                    :key="suggestion.id" type="button"
                                                                    class="city-suggestion-item"
                                                                    @mousedown.prevent="selectBrideResidenceSuggestion(suggestion)">
                                                                    <span class="city-suggestion-name">{{
                                                                        suggestion.mainText }}</span>
                                                                    <span class="city-suggestion-context">{{
                                                                        suggestion.displayName }}</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <small class="text-white-50 smallest mt-2 d-block italic"><i
                                                        class="bi bi-info-circle me-1"></i>Tips: Please enter complete
                                                    details for legal records.</small>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label class="form-label">Degree of relationship (Contracting
                                                    Parties)</label>
                                                <input class="form-control" v-model="form.bride.degree">
                                                <small class="text-white-50 smallest">Leave blank if not
                                                    applicable.</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="isPreviouslyMarried" class="glass-inner p-4 mb-4 border border-info">
                                        <h4 class="section-title mb-4">Dissolution of Previous Marriage</h4>
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label">How was it dissolved?</label>
                                                <textarea class="form-control" rows="2"
                                                    v-model="form.bride.previousMarriageDissolve"></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Place where dissolved</label>
                                                <input class="form-control"
                                                    v-model="form.bride.dissolvedCityMunicipality"
                                                    @input="onBrideDissolvedPlaceInput"
                                                    list="bride-dissolved-place-suggestions" autocomplete="off"
                                                    placeholder="City/Municipality, Province, Country">
                                                <datalist id="bride-dissolved-place-suggestions">
                                                    <option v-for="suggestion in brideDissolvedPlaceSuggestions"
                                                        :key="suggestion.id"
                                                        :value="suggestion.formatted || suggestion.cityMunicipality">
                                                        {{ suggestion.context }}
                                                    </option>
                                                </datalist>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Date when dissolved</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control"
                                                        v-model="form.bride.dissolvedDay"
                                                        list="bride-dissolved-day-suggestions" placeholder="Day">
                                                    <input type="text" class="form-control"
                                                        v-model="form.bride.dissolvedMonth"
                                                        list="bride-dissolved-month-suggestions" placeholder="Month">
                                                    <input type="text" class="form-control"
                                                        v-model="form.bride.dissolvedYear"
                                                        list="bride-dissolved-year-suggestions" placeholder="Year">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="glass-inner p-4 mb-4">
                                        <h4 class="section-title mb-4">Parents' Information</h4>

                                        <div class="row g-3 mb-4 border-bottom border-white-10 pb-4">
                                            <div class="col-12"><small class="text-white-50 uppercase fw-bold">Father's
                                                    Details</small></div>
                                            <div class="col-md-4"><input class="form-control"
                                                    v-model="form.bride.fatherFirstName" placeholder="First Name"></div>
                                            <div class="col-md-4"><input class="form-control"
                                                    v-model="form.bride.fatherMiddleName" placeholder="Middle Name">
                                            </div>
                                            <div class="col-md-4"><input class="form-control"
                                                    v-model="form.bride.fatherLastName" placeholder="Last Name"></div>
                                            <div class="col-md-4">
                                                <select class="form-control" v-model="form.bride.fatherCitizenship">
                                                    <option value="" disabled>Father's Citizenship</option>
                                                    <option value="Filipino">Filipino</option>
                                                    <option value="Others">Others</option>
                                                </select>
                                            </div>
                                            <div v-if="form.bride.fatherCitizenship === 'Others'"
                                                class="col-md-8 animate-fade-in">
                                                <input class="form-control" v-model="form.bride.fatherCitizenshipOther"
                                                    list="father-citizenship-options"
                                                    placeholder="Specify father's citizenship">
                                            </div>
                                            <div class="row g-2">
                                                <label class="form-label">Full Residence</label>
                                                <div class="col-md-5">
                                                    <div class="position-relative">
                                                        <input class="form-control"
                                                            v-model="form.bride.fatherHouseNumStreet" autocomplete="off"
                                                            placeholder="House no., Street">
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="position-relative">
                                                        <input class="form-control" v-model="form.bride.fatherResidence"
                                                            @input="onBrideRelatedResidenceInput('fatherResidence')"
                                                            @focus="onBrideRelatedResidenceFocus('fatherResidence')"
                                                            @blur="onBrideRelatedResidenceBlur" autocomplete="off"
                                                            placeholder="Barangay, City/Municipality, Province, Country, Zip code">
                                                        <div v-if="showBrideRelatedResidenceSuggestions && brideRelatedResidenceField === 'fatherResidence' && brideRelatedResidenceSuggestions.length"
                                                            class="city-suggestion-menu">
                                                            <button
                                                                v-for="suggestion in brideRelatedResidenceSuggestions"
                                                                :key="suggestion.id" type="button"
                                                                class="city-suggestion-item"
                                                                @mousedown.prevent="selectBrideRelatedResidenceSuggestion(suggestion)">
                                                                <span class="city-suggestion-name">{{
                                                                    suggestion.mainText }}</span>
                                                                <span class="city-suggestion-context">{{
                                                                    suggestion.displayName }}</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-12"><small class="text-white-50 uppercase fw-bold">Mother's
                                                    Maiden Details</small></div>
                                            <div class="col-md-4"><input class="form-control"
                                                    v-model="form.bride.motherMaidenFirstName" placeholder="First Name">
                                            </div>
                                            <div class="col-md-4"><input class="form-control"
                                                    v-model="form.bride.motherMaidenMiddleName"
                                                    placeholder="Middle Name"></div>
                                            <div class="col-md-4"><input class="form-control"
                                                    v-model="form.bride.motherMaidenLastName" placeholder="Last Name">
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control"
                                                    v-model="form.bride.motherMaidenCitizenship">
                                                    <option value="" disabled>Mother's Citizenship</option>
                                                    <option value="Filipino">Filipino</option>
                                                    <option value="Others">Others</option>
                                                </select>
                                            </div>
                                            <div v-if="form.bride.motherMaidenCitizenship === 'Others'"
                                                class="col-md-8 animate-fade-in">
                                                <input class="form-control"
                                                    v-model="form.bride.motherMaidenCitizenshipOther"
                                                    list="mother-citizenship-options"
                                                    placeholder="Specify mother's citizenship">
                                            </div>
                                            <div class="row g-2">
                                                <label class="form-label">Full Residence</label>
                                                <div class="col-md-5">
                                                    <div class="position-relative">
                                                        <input class="form-control"
                                                            v-model="form.bride.motherHouseNumStreet" autocomplete="off"
                                                            placeholder="House no., Street">
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="position-relative">
                                                        <input class="form-control"
                                                            v-model="form.bride.motherMaidenResidence"
                                                            @input="onBrideRelatedResidenceInput('motherMaidenResidence')"
                                                            @focus="onBrideRelatedResidenceFocus('motherMaidenResidence')"
                                                            @blur="onBrideRelatedResidenceBlur" autocomplete="off"
                                                            placeholder="Barangay, City/Municipality, Province, Country, Zip code">
                                                        <div v-if="showBrideRelatedResidenceSuggestions && brideRelatedResidenceField === 'motherMaidenResidence' && brideRelatedResidenceSuggestions.length"
                                                            class="city-suggestion-menu">
                                                            <button
                                                                v-for="suggestion in brideRelatedResidenceSuggestions"
                                                                :key="suggestion.id" type="button"
                                                                class="city-suggestion-item"
                                                                @mousedown.prevent="selectBrideRelatedResidenceSuggestion(suggestion)">
                                                                <span class="city-suggestion-name">{{
                                                                    suggestion.mainText }}</span>
                                                                <span class="city-suggestion-context">{{
                                                                    suggestion.displayName }}</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="brideRequirement !== 'no-need'"
                                        class="glass-inner p-4 mb-4 border border-warning">
                                        <h4 class="section-title mb-4">Person Providing {{ brideRequirement ===
                                            'parental-consent' ? 'Consent' : 'Advice' }}</h4>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">First Name</label>
                                                <input class="form-control"
                                                    v-model="form.consentSource.bride.firstName">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Middle Name</label>
                                                <input class="form-control"
                                                    v-model="form.consentSource.bride.middleName">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Last Name</label>
                                                <input class="form-control" v-model="form.consentSource.bride.lastName">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Citizenship</label>
                                                <select class="form-control"
                                                    v-model="form.consentSource.bride.citizenship">
                                                    <option value="" disabled>Select Citizenship</option>
                                                    <option value="Filipino">Filipino</option>
                                                    <option value="Others">Others</option>
                                                </select>
                                            </div>
                                            <div v-if="form.consentSource.bride.citizenship === 'Others'"
                                                class="col-md-6 animate-fade-in">
                                                <label class="form-label">Specify Citizenship</label>
                                                <input class="form-control"
                                                    v-model="form.consentSource.bride.citizenshipOther"
                                                    list="source-citizenship-options" placeholder="Specify citizenship">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Relationship</label>
                                                <input class="form-control"
                                                    v-model="form.consentSource.bride.relationship"
                                                    placeholder="e.g. Mother / Guardian">
                                            </div>

                                            <div class="row g-2">
                                                <label class="form-label">Full Residence</label>
                                                <div class="col-md-5">
                                                    <div class="position-relative">
                                                        <input class="form-control"
                                                            v-model="form.consentSource.bride.houseNumStreet"
                                                            autocomplete="off" placeholder="House no., Street">
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="position-relative">
                                                        <input class="form-control"
                                                            v-model="form.consentSource.bride.residence"
                                                            @input="onBrideRelatedResidenceInput('consentSourceResidence')"
                                                            @focus="onBrideRelatedResidenceFocus('consentSourceResidence')"
                                                            @blur="onBrideRelatedResidenceBlur" autocomplete="off"
                                                            placeholder="Barangay, City/Municipality, Province, Country, Zip code">
                                                        <div v-if="showBrideRelatedResidenceSuggestions && brideRelatedResidenceField === 'consentSourceResidence' && brideRelatedResidenceSuggestions.length"
                                                            class="city-suggestion-menu">
                                                            <button
                                                                v-for="suggestion in brideRelatedResidenceSuggestions"
                                                                :key="suggestion.id" type="button"
                                                                class="city-suggestion-item"
                                                                @mousedown.prevent="selectBrideRelatedResidenceSuggestion(suggestion)">
                                                                <span class="city-suggestion-name">{{
                                                                    suggestion.mainText }}</span>
                                                                <span class="city-suggestion-context">{{
                                                                    suggestion.displayName }}</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!-- STEP 37 – REVIEW -->
                                <div v-if="step === 4">
                                    <div class="text-center mb-4">
                                        <h4 class="section-title border-0 mb-1">Final Verification</h4>
                                        <p class="text-white-50 small">Please review all information before submitting
                                            the application.</p>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div
                                                class="glass-inner p-4 h-100 border-primary-subtle border-top border-4">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h6
                                                        class="text-primary-subtle fw-bold text-uppercase tracking-wider small mb-0">
                                                        <i class="bi bi-person-fill me-2"></i>Groom's Profile
                                                    </h6>
                                                    <span class="badge rounded-pill bg-primary px-3">Primary</span>
                                                </div>

                                                <div class="final-review-content text-white">
                                                    <div class="mb-4">
                                                        <label
                                                            class="text-white-50 smallest text-uppercase d-block">Full
                                                            Name</label>
                                                        <h5 class="fw-bold mb-0">{{ form.groom.lastName }}, {{
                                                            form.groom.firstName }} {{ form.groom.middleName }}</h5>
                                                    </div>

                                                    <div class="row g-3">
                                                        <div class="col-6">
                                                            <label
                                                                class="text-white-50 smallest text-uppercase d-block">Age
                                                                / Status</label>
                                                            <p class="mb-0 fw-semibold">{{ form.groom.age }} — {{
                                                                form.groom.civilStatus }}</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <label
                                                                class="text-white-50 smallest text-uppercase d-block">Religion</label>
                                                            <p class="mb-0 fw-semibold">{{ form.groom.religion || 'N/A'
                                                                }}</p>
                                                        </div>
                                                        <div class="col-12">
                                                            <label
                                                                class="text-white-50 smallest text-uppercase d-block">Citizenship</label>
                                                            <p class="mb-0 fw-semibold">{{ form.groom.citizenship }}</p>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="p-2 rounded bg-white bg-opacity-10">
                                                                <label
                                                                    class="text-white-50 smallest text-uppercase d-block">Current
                                                                    Residence</label>
                                                                <p class="small mb-0 lh-sm">{{ form.groom.residence }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="glass-inner p-4 h-100 border-info-subtle border-top border-4">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h6
                                                        class="text-info-subtle fw-bold text-uppercase tracking-wider small mb-0">
                                                        <i class="bi bi-person-heart me-2"></i>Bride's Profile
                                                    </h6>
                                                    <span
                                                        class="badge rounded-pill bg-info text-dark px-3">Primary</span>
                                                </div>

                                                <div class="final-review-content text-white">
                                                    <div class="mb-4">
                                                        <label
                                                            class="text-white-50 smallest text-uppercase d-block">Full
                                                            Name</label>
                                                        <h5 class="fw-bold mb-0">{{ form.bride.lastName }}, {{
                                                            form.bride.firstName }} {{ form.bride.middleName }}</h5>
                                                    </div>

                                                    <div class="row g-3">
                                                        <div class="col-6">
                                                            <label
                                                                class="text-white-50 smallest text-uppercase d-block">Age
                                                                / Status</label>
                                                            <p class="mb-0 fw-semibold">{{ form.bride.age }} — {{
                                                                form.bride.civilStatus }}</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <label
                                                                class="text-white-50 smallest text-uppercase d-block">Religion</label>
                                                            <p class="mb-0 fw-semibold">{{ form.bride.religion || 'N/A'
                                                                }}</p>
                                                        </div>
                                                        <div class="col-12">
                                                            <label
                                                                class="text-white-50 smallest text-uppercase d-block">Citizenship</label>
                                                            <p class="mb-0 fw-semibold">{{ form.bride.citizenship }}</p>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="p-2 rounded bg-white bg-opacity-10">
                                                                <label
                                                                    class="text-white-50 smallest text-uppercase d-block">Current
                                                                    Residence</label>
                                                                <p class="small mb-0 lh-sm">{{ form.bride.residence }}
                                                                </p>
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
                                            By proceeding, you confirm that the information provided above is true and
                                            correct to the best of your knowledge.
                                        </p>
                                    </div>
                                </div>

                                <!-- NAVIGATION -->
                                <div class="d-flex justify-content-between mt-5">
                                    <button class="btn btn-outline-light" :disabled="step === 1" @click="goBack()">
                                        Back
                                    </button>

                                    <button v-if="step < totalSteps && step != 4" class="btn btn-primary glow-button"
                                        @click="goNext()">
                                        Next
                                    </button>

                                    <button v-else class="btn btn-success glow-button" @click="submitForm">
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
            ageRequirements: null,
            message: [],
            step: 1,
            totalSteps: 4,
            groomAgeError: '',
            brideAgeError: '',
            isPreviouslyMarried: false,
            groomCitySuggestions: [],
            showGroomCitySuggestions: false,
            groomCityDebounceTimer: null,
            groomCityAbortController: null,
            groomCityBlurTimer: null,
            groomResidenceSuggestions: [],
            showGroomResidenceSuggestions: false,
            groomResidenceDebounceTimer: null,
            groomResidenceAbortController: null,
            groomResidenceBlurTimer: null,
            groomRelatedResidenceField: '',
            groomRelatedResidenceSuggestions: [],
            showGroomRelatedResidenceSuggestions: false,
            groomRelatedResidenceDebounceTimer: null,
            groomRelatedResidenceAbortController: null,
            groomRelatedResidenceBlurTimer: null,
            groomDissolvedPlaceSuggestions: [],
            showGroomDissolvedPlaceSuggestions: false,
            groomDissolvedPlaceDebounceTimer: null,
            groomDissolvedPlaceAbortController: null,
            groomDissolvedPlaceBlurTimer: null,
            brideRelatedResidenceField: '',
            brideRelatedResidenceSuggestions: [],
            showBrideRelatedResidenceSuggestions: false,
            brideRelatedResidenceDebounceTimer: null,
            brideRelatedResidenceAbortController: null,
            brideRelatedResidenceBlurTimer: null,
            brideResidenceSuggestions: [],
            showBrideResidenceSuggestions: false,
            brideResidenceDebounceTimer: null,
            brideResidenceAbortController: null,
            brideResidenceBlurTimer: null,
            brideCitySuggestions: [],
            showBrideCitySuggestions: false,
            brideCityDebounceTimer: null,
            brideCityAbortController: null,
            brideCityBlurTimer: null,
            brideDissolvedPlaceSuggestions: [],
            brideDissolvedPlaceDebounceTimer: null,
            brideDissolvedPlaceAbortController: null,
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
    computed: {
        monthOptions() {
            return [
                { value: 1, label: 'January' },
                { value: 2, label: 'February' },
                { value: 3, label: 'March' },
                { value: 4, label: 'April' },
                { value: 5, label: 'May' },
                { value: 6, label: 'June' },
                { value: 7, label: 'July' },
                { value: 8, label: 'August' },
                { value: 9, label: 'September' },
                { value: 10, label: 'October' },
                { value: 11, label: 'November' },
                { value: 12, label: 'December' }
            ];
        },
        groomYearOptions() {
            return this.getYearOptions(this.groomRequirement);
        },
        brideYearOptions() {
            return this.getYearOptions(this.brideRequirement);
        },
        groomDayOptions() {
            return this.getDayOptions(this.form.groom.year, this.form.groom.month);
        },
        brideDayOptions() {
            return this.getDayOptions(this.form.bride.year, this.form.bride.month);
        },
        dissolvedYearOptions() {
            const currentYear = new Date().getFullYear();
            const years = [];
            for (let year = currentYear; year >= 1900; year--) {
                years.push(year);
            }
            return years;
        },
        groomDissolvedDayOptions() {
            return this.getDayOptions(this.form.groom.dissolvedYear, this.form.groom.dissolvedMonth);
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
        getYearOptions(requirement) {
            const currentYear = new Date().getFullYear();
            const { min, max } = this.getAgeLimits(requirement);
            const youngestBirthYear = currentYear - min;
            const oldestBirthYear = currentYear - max;
            const years = [];

            for (let year = youngestBirthYear; year >= oldestBirthYear; year--) {
                years.push(year);
            }

            return years;
        },
        getDayOptions(year, month) {
            const monthNum = Number(month);
            if (!monthNum || monthNum < 1 || monthNum > 12) return [];

            const yearNum = Number(year);
            const safeYear = Number.isFinite(yearNum) && yearNum > 0 ? yearNum : 2000;
            const daysInMonth = new Date(safeYear, monthNum, 0).getDate();

            return Array.from({ length: daysInMonth }, (_, index) => index + 1);
        },
        parseMonthValue(monthInput) {
            if (monthInput === null || monthInput === undefined) return null;
            const raw = String(monthInput).trim();
            if (!raw) return null;

            const asNumber = Number(raw);
            if (Number.isInteger(asNumber) && asNumber >= 1 && asNumber <= 12) {
                return asNumber;
            }

            const normalized = raw.toLowerCase();
            const monthIndex = this.monthOptions.findIndex((month) =>
                month.label.toLowerCase().startsWith(normalized) || normalized.startsWith(month.label.toLowerCase())
            );

            return monthIndex >= 0 ? this.monthOptions[monthIndex].value : null;
        },
        isValidFullDate(dayInput, monthInput, yearInput) {
            const day = Number(dayInput);
            const month = this.parseMonthValue(monthInput);
            const year = Number(yearInput);

            if (!day || !month || !year) return false;
            const candidate = new Date(year, month - 1, day);
            return candidate.getFullYear() === year &&
                candidate.getMonth() === month - 1 &&
                candidate.getDate() === day;
        },
        isValidCalendarDate(day, month, year) {
            const dayNum = Number(day);
            const monthNum = Number(month);
            const yearNum = Number(year);

            if (!dayNum || !monthNum || !yearNum) return false;

            const candidate = new Date(yearNum, monthNum - 1, dayNum);
            return candidate.getFullYear() === yearNum &&
                candidate.getMonth() === monthNum - 1 &&
                candidate.getDate() === dayNum;
        },
        calculateAge(day, month, year) {
            const dayNum = Number(day);
            const monthNum = Number(month);
            const yearNum = Number(year);

            const today = new Date();
            const birthDate = new Date(yearNum, monthNum - 1, dayNum);
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            return age;
        },
        setAgeError(type, message) {
            if (type === 'groom') {
                this.groomAgeError = message;
            } else {
                this.brideAgeError = message;
            }
        },
        getResidenceAddressParts(address, placeName = '') {
            if (!address || typeof address !== 'object') {
                return {
                    houseNumber: '',
                    street: '',
                    barangay: '',
                    cityMunicipality: '',
                    province: '',
                    country: '',
                    zipCode: ''
                };
            }

            const cityMunicipality =
                address.city ||
                address.town ||
                address.municipality ||
                address.village ||
                address.county ||
                '';
            const province =
                address.state ||
                address.province ||
                address.region ||
                address.state_district ||
                '';
            const country = address.country || '';

            let barangay =
                address.suburb ||
                address.neighbourhood ||
                address.quarter ||
                address.city_district ||
                address.borough ||
                address.hamlet ||
                '';

            const normalizedPlaceName = (placeName || '').trim();
            const lowerPlaceName = normalizedPlaceName.toLowerCase();
            const blockedValues = new Set(
                [cityMunicipality, province, country].filter(Boolean).map((v) => v.toLowerCase())
            );
            if (!barangay && normalizedPlaceName && !blockedValues.has(lowerPlaceName)) {
                barangay = normalizedPlaceName;
            }

            return {
                houseNumber: address.house_number || '',
                street:
                    address.road ||
                    address.pedestrian ||
                    address.path ||
                    address.residential ||
                    '',
                barangay,
                cityMunicipality,
                province,
                country,
                zipCode: address.postcode || ''
            };
        },
        formatResidenceAddress(address, fallbackDisplayName = '', placeName = '', includeHouseStreet = true) {
            if (!address || typeof address !== 'object') return fallbackDisplayName;

            const parts = this.getResidenceAddressParts(address, placeName);
            const orderedParts = [
                includeHouseStreet ? parts.houseNumber : '',
                includeHouseStreet ? parts.street : '',
                parts.barangay,
                parts.cityMunicipality,
                parts.province,
                parts.country,
                parts.zipCode
            ].filter(Boolean);

            return orderedParts.length ? orderedParts.join(', ') : fallbackDisplayName;
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
        onGroomResidenceInput() {
            const query = (this.form.groom.residence || '').trim();
            this.showGroomResidenceSuggestions = true;

            if (this.groomResidenceDebounceTimer) {
                clearTimeout(this.groomResidenceDebounceTimer);
            }

            if (this.groomResidenceAbortController) {
                this.groomResidenceAbortController.abort();
                this.groomResidenceAbortController = null;
            }

            if (query.length < 5) {
                this.groomResidenceSuggestions = [];
                return;
            }

            this.groomResidenceDebounceTimer = setTimeout(() => {
                this.fetchGroomResidenceSuggestions(query);
            }, 350);
        },
        async fetchGroomResidenceSuggestions(query) {
            this.groomResidenceAbortController = new AbortController();

            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=jsonv2&addressdetails=1&limit=8&dedupe=0&q=${encodeURIComponent(query)}`,
                    {
                        signal: this.groomResidenceAbortController.signal,
                        headers: { 'Accept-Language': 'en' }
                    }
                );

                if (!response.ok) {
                    this.groomResidenceSuggestions = [];
                    return;
                }

                const data = await response.json();
                const suggestions = data
                    .map((item, index) => {
                        const address = item.address || {};
                        const countryCode = (address.country_code || '').toLowerCase();
                        const displayName = item.display_name || '';
                        const mainText =
                            item.name ||
                            address.road ||
                            address.suburb ||
                            address.city ||
                            address.town ||
                            address.municipality ||
                            displayName.split(',')[0] ||
                            '';

                        if (!displayName) return null;

                        let score = 0;
                        if (countryCode === 'ph') score += 30;
                        if (displayName.toLowerCase().startsWith(query.toLowerCase())) score += 10;

                        return {
                            id: item.place_id || `res-${index}`,
                            mainText,
                            displayName,
                            addressParts: this.getResidenceAddressParts(address, item.name || ''),
                            formattedAddress: this.formatResidenceAddress(address, displayName, item.name || ''),
                            formattedResidenceOnly: this.formatResidenceAddress(address, displayName, item.name || '', false),
                            score
                        };
                    })
                    .filter(Boolean)
                    .sort((a, b) => b.score - a.score);

                const uniqueByDisplay = new Map();
                suggestions.forEach((item) => {
                    if (!uniqueByDisplay.has(item.displayName.toLowerCase())) {
                        uniqueByDisplay.set(item.displayName.toLowerCase(), item);
                    }
                });

                this.groomResidenceSuggestions = Array.from(uniqueByDisplay.values()).slice(0, 6);
            } catch (error) {
                if (error.name !== 'AbortError') {
                    this.groomResidenceSuggestions = [];
                }
            } finally {
                this.groomResidenceAbortController = null;
            }
        },
        onGroomResidenceFocus() {
            if (this.groomResidenceBlurTimer) {
                clearTimeout(this.groomResidenceBlurTimer);
                this.groomResidenceBlurTimer = null;
            }
            this.showGroomResidenceSuggestions = this.groomResidenceSuggestions.length > 0;
        },
        onGroomResidenceBlur() {
            this.groomResidenceBlurTimer = setTimeout(() => {
                this.showGroomResidenceSuggestions = false;
            }, 120);
        },
        selectGroomResidenceSuggestion(selected) {
            const parts = selected.addressParts || {};
            const mergedStreetHouse = [parts.houseNumber, parts.street].filter(Boolean).join(' ').trim();
            if (!this.form.groom.streethousenum && mergedStreetHouse) {
                this.form.groom.streethousenum = mergedStreetHouse;
            }
            this.form.groom.residence = selected.formattedResidenceOnly || selected.formattedAddress || selected.displayName;
            this.showGroomResidenceSuggestions = false;
        },
        getGroomRelatedResidenceValue(fieldKey) {
            if (fieldKey === 'fatherResidence') return this.form.groom.fatherResidence || '';
            if (fieldKey === 'motherMaidenResidence') return this.form.groom.motherMaidenResidence || '';
            if (fieldKey === 'consentSourceResidence') return this.form.consentSource.groom.residence || '';
            return '';
        },
        setGroomRelatedResidenceValue(fieldKey, value) {
            if (fieldKey === 'fatherResidence') this.form.groom.fatherResidence = value;
            if (fieldKey === 'motherMaidenResidence') this.form.groom.motherMaidenResidence = value;
            if (fieldKey === 'consentSourceResidence') this.form.consentSource.groom.residence = value;
        },
        onGroomRelatedResidenceInput(fieldKey) {
            const query = this.getGroomRelatedResidenceValue(fieldKey).trim();
            this.groomRelatedResidenceField = fieldKey;
            this.showGroomRelatedResidenceSuggestions = true;

            if (this.groomRelatedResidenceDebounceTimer) {
                clearTimeout(this.groomRelatedResidenceDebounceTimer);
            }

            if (this.groomRelatedResidenceAbortController) {
                this.groomRelatedResidenceAbortController.abort();
                this.groomRelatedResidenceAbortController = null;
            }

            if (query.length < 5) {
                this.groomRelatedResidenceSuggestions = [];
                return;
            }

            this.groomRelatedResidenceDebounceTimer = setTimeout(() => {
                this.fetchGroomRelatedResidenceSuggestions(fieldKey, query);
            }, 350);
        },
        async fetchGroomRelatedResidenceSuggestions(fieldKey, query) {
            this.groomRelatedResidenceAbortController = new AbortController();

            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=jsonv2&addressdetails=1&limit=8&dedupe=0&q=${encodeURIComponent(query)}`,
                    {
                        signal: this.groomRelatedResidenceAbortController.signal,
                        headers: { 'Accept-Language': 'en' }
                    }
                );

                if (!response.ok) {
                    this.groomRelatedResidenceSuggestions = [];
                    return;
                }

                const data = await response.json();
                const suggestions = data
                    .map((item, index) => {
                        const address = item.address || {};
                        const countryCode = (address.country_code || '').toLowerCase();
                        const displayName = item.display_name || '';
                        const mainText =
                            item.name ||
                            address.road ||
                            address.suburb ||
                            address.city ||
                            address.town ||
                            address.municipality ||
                            displayName.split(',')[0] ||
                            '';

                        if (!displayName) return null;

                        let score = 0;
                        if (countryCode === 'ph') score += 30;
                        if (displayName.toLowerCase().startsWith(query.toLowerCase())) score += 10;

                        return {
                            id: item.place_id || `g-rel-res-${index}`,
                            mainText,
                            displayName,
                            formattedAddress: this.formatResidenceAddress(address, displayName, item.name || ''),
                            score
                        };
                    })
                    .filter(Boolean)
                    .sort((a, b) => b.score - a.score);

                if (this.groomRelatedResidenceField !== fieldKey) return;

                const uniqueByDisplay = new Map();
                suggestions.forEach((item) => {
                    if (!uniqueByDisplay.has(item.displayName.toLowerCase())) {
                        uniqueByDisplay.set(item.displayName.toLowerCase(), item);
                    }
                });

                this.groomRelatedResidenceSuggestions = Array.from(uniqueByDisplay.values()).slice(0, 6);
            } catch (error) {
                if (error.name !== 'AbortError') {
                    this.groomRelatedResidenceSuggestions = [];
                }
            } finally {
                this.groomRelatedResidenceAbortController = null;
            }
        },
        onGroomRelatedResidenceFocus(fieldKey) {
            this.groomRelatedResidenceField = fieldKey;
            if (this.groomRelatedResidenceBlurTimer) {
                clearTimeout(this.groomRelatedResidenceBlurTimer);
                this.groomRelatedResidenceBlurTimer = null;
            }
            this.showGroomRelatedResidenceSuggestions = this.groomRelatedResidenceSuggestions.length > 0;
        },
        onGroomRelatedResidenceBlur() {
            this.groomRelatedResidenceBlurTimer = setTimeout(() => {
                this.showGroomRelatedResidenceSuggestions = false;
            }, 120);
        },
        selectGroomRelatedResidenceSuggestion(selected) {
            if (!this.groomRelatedResidenceField) return;
            this.setGroomRelatedResidenceValue(
                this.groomRelatedResidenceField,
                selected.formattedAddress || selected.displayName
            );
            this.showGroomRelatedResidenceSuggestions = false;
        },
        onGroomDissolvedPlaceInput() {
            const query = (this.form.groom.dissolvedCityMunicipality || '').trim();
            this.showGroomDissolvedPlaceSuggestions = true;

            if (this.groomDissolvedPlaceDebounceTimer) {
                clearTimeout(this.groomDissolvedPlaceDebounceTimer);
            }

            if (this.groomDissolvedPlaceAbortController) {
                this.groomDissolvedPlaceAbortController.abort();
                this.groomDissolvedPlaceAbortController = null;
            }

            if (query.length < 3) {
                this.groomDissolvedPlaceSuggestions = [];
                return;
            }

            this.groomDissolvedPlaceDebounceTimer = setTimeout(() => {
                this.fetchGroomDissolvedPlaceSuggestions(query);
            }, 350);
        },
        async fetchGroomDissolvedPlaceSuggestions(query) {
            this.groomDissolvedPlaceAbortController = new AbortController();

            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=jsonv2&addressdetails=1&limit=8&dedupe=0&q=${encodeURIComponent(query)}`,
                    {
                        signal: this.groomDissolvedPlaceAbortController.signal,
                        headers: { 'Accept-Language': 'en' }
                    }
                );

                if (!response.ok) {
                    this.groomDissolvedPlaceSuggestions = [];
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
                        if (countryCode === 'ph') score += 40;

                        return {
                            id: item.place_id || `g-dis-place-${index}`,
                            cityMunicipality,
                            province,
                            country,
                            context: [province, country].filter(Boolean).join(', '),
                            formatted: [cityMunicipality, province, country].filter(Boolean).join(', '),
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

                this.groomDissolvedPlaceSuggestions = Array.from(uniqueByLocation.values()).slice(0, 6);
            } catch (error) {
                if (error.name !== 'AbortError') {
                    this.groomDissolvedPlaceSuggestions = [];
                }
            } finally {
                this.groomDissolvedPlaceAbortController = null;
            }
        },
        onGroomDissolvedPlaceFocus() {
            if (this.groomDissolvedPlaceBlurTimer) {
                clearTimeout(this.groomDissolvedPlaceBlurTimer);
                this.groomDissolvedPlaceBlurTimer = null;
            }
            this.showGroomDissolvedPlaceSuggestions = this.groomDissolvedPlaceSuggestions.length > 0;
        },
        onGroomDissolvedPlaceBlur() {
            this.groomDissolvedPlaceBlurTimer = setTimeout(() => {
                this.showGroomDissolvedPlaceSuggestions = false;
            }, 120);
        },
        selectGroomDissolvedPlaceSuggestion(selected) {
            this.form.groom.dissolvedCityMunicipality = selected.formatted || selected.cityMunicipality;
            this.showGroomDissolvedPlaceSuggestions = false;
        },
        getBrideRelatedResidenceValue(fieldKey) {
            if (fieldKey === 'fatherResidence') return this.form.bride.fatherResidence || '';
            if (fieldKey === 'motherMaidenResidence') return this.form.bride.motherMaidenResidence || '';
            if (fieldKey === 'consentSourceResidence') return this.form.consentSource.bride.residence || '';
            return '';
        },
        setBrideRelatedResidenceValue(fieldKey, value) {
            if (fieldKey === 'fatherResidence') this.form.bride.fatherResidence = value;
            if (fieldKey === 'motherMaidenResidence') this.form.bride.motherMaidenResidence = value;
            if (fieldKey === 'consentSourceResidence') this.form.consentSource.bride.residence = value;
        },
        onBrideRelatedResidenceInput(fieldKey) {
            const query = this.getBrideRelatedResidenceValue(fieldKey).trim();
            this.brideRelatedResidenceField = fieldKey;
            this.showBrideRelatedResidenceSuggestions = true;

            if (this.brideRelatedResidenceDebounceTimer) {
                clearTimeout(this.brideRelatedResidenceDebounceTimer);
            }

            if (this.brideRelatedResidenceAbortController) {
                this.brideRelatedResidenceAbortController.abort();
                this.brideRelatedResidenceAbortController = null;
            }

            if (query.length < 5) {
                this.brideRelatedResidenceSuggestions = [];
                return;
            }

            this.brideRelatedResidenceDebounceTimer = setTimeout(() => {
                this.fetchBrideRelatedResidenceSuggestions(fieldKey, query);
            }, 350);
        },
        async fetchBrideRelatedResidenceSuggestions(fieldKey, query) {
            this.brideRelatedResidenceAbortController = new AbortController();

            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=jsonv2&addressdetails=1&limit=8&dedupe=0&q=${encodeURIComponent(query)}`,
                    {
                        signal: this.brideRelatedResidenceAbortController.signal,
                        headers: { 'Accept-Language': 'en' }
                    }
                );

                if (!response.ok) {
                    this.brideRelatedResidenceSuggestions = [];
                    return;
                }

                const data = await response.json();
                const suggestions = data
                    .map((item, index) => {
                        const address = item.address || {};
                        const countryCode = (address.country_code || '').toLowerCase();
                        const displayName = item.display_name || '';
                        const mainText =
                            item.name ||
                            address.road ||
                            address.suburb ||
                            address.city ||
                            address.town ||
                            address.municipality ||
                            displayName.split(',')[0] ||
                            '';

                        if (!displayName) return null;

                        let score = 0;
                        if (countryCode === 'ph') score += 30;
                        if (displayName.toLowerCase().startsWith(query.toLowerCase())) score += 10;

                        return {
                            id: item.place_id || `b-rel-res-${index}`,
                            mainText,
                            displayName,
                            formattedAddress: this.formatResidenceAddress(address, displayName, item.name || ''),
                            score
                        };
                    })
                    .filter(Boolean)
                    .sort((a, b) => b.score - a.score);

                if (this.brideRelatedResidenceField !== fieldKey) return;

                const uniqueByDisplay = new Map();
                suggestions.forEach((item) => {
                    if (!uniqueByDisplay.has(item.displayName.toLowerCase())) {
                        uniqueByDisplay.set(item.displayName.toLowerCase(), item);
                    }
                });

                this.brideRelatedResidenceSuggestions = Array.from(uniqueByDisplay.values()).slice(0, 6);
            } catch (error) {
                if (error.name !== 'AbortError') {
                    this.brideRelatedResidenceSuggestions = [];
                }
            } finally {
                this.brideRelatedResidenceAbortController = null;
            }
        },
        onBrideRelatedResidenceFocus(fieldKey) {
            this.brideRelatedResidenceField = fieldKey;
            if (this.brideRelatedResidenceBlurTimer) {
                clearTimeout(this.brideRelatedResidenceBlurTimer);
                this.brideRelatedResidenceBlurTimer = null;
            }
            this.showBrideRelatedResidenceSuggestions = this.brideRelatedResidenceSuggestions.length > 0;
        },
        onBrideRelatedResidenceBlur() {
            this.brideRelatedResidenceBlurTimer = setTimeout(() => {
                this.showBrideRelatedResidenceSuggestions = false;
            }, 120);
        },
        selectBrideRelatedResidenceSuggestion(selected) {
            if (!this.brideRelatedResidenceField) return;
            this.setBrideRelatedResidenceValue(
                this.brideRelatedResidenceField,
                selected.formattedAddress || selected.displayName
            );
            this.showBrideRelatedResidenceSuggestions = false;
        },
        onBrideDissolvedPlaceInput() {
            const query = (this.form.bride.dissolvedCityMunicipality || '').trim();

            if (this.brideDissolvedPlaceDebounceTimer) {
                clearTimeout(this.brideDissolvedPlaceDebounceTimer);
            }
            if (this.brideDissolvedPlaceAbortController) {
                this.brideDissolvedPlaceAbortController.abort();
                this.brideDissolvedPlaceAbortController = null;
            }
            if (query.length < 3) {
                this.brideDissolvedPlaceSuggestions = [];
                return;
            }
            this.brideDissolvedPlaceDebounceTimer = setTimeout(() => {
                this.fetchBrideDissolvedPlaceSuggestions(query);
            }, 350);
        },
        async fetchBrideDissolvedPlaceSuggestions(query) {
            this.brideDissolvedPlaceAbortController = new AbortController();

            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=jsonv2&addressdetails=1&limit=8&dedupe=0&q=${encodeURIComponent(query)}`,
                    {
                        signal: this.brideDissolvedPlaceAbortController.signal,
                        headers: { 'Accept-Language': 'en' }
                    }
                );

                if (!response.ok) {
                    this.brideDissolvedPlaceSuggestions = [];
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
                        if (countryCode === 'ph') score += 40;

                        return {
                            id: item.place_id || `b-dis-place-${index}`,
                            cityMunicipality,
                            province,
                            country,
                            context: [province, country].filter(Boolean).join(', '),
                            formatted: [cityMunicipality, province, country].filter(Boolean).join(', '),
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

                this.brideDissolvedPlaceSuggestions = Array.from(uniqueByLocation.values()).slice(0, 6);
            } catch (error) {
                if (error.name !== 'AbortError') {
                    this.brideDissolvedPlaceSuggestions = [];
                }
            } finally {
                this.brideDissolvedPlaceAbortController = null;
            }
        },
        onBrideCityInput() {
            const query = (this.form.bride.cityMunicipality || '').trim();
            this.showBrideCitySuggestions = true;

            if (this.brideCityDebounceTimer) {
                clearTimeout(this.brideCityDebounceTimer);
            }

            if (this.brideCityAbortController) {
                this.brideCityAbortController.abort();
                this.brideCityAbortController = null;
            }

            if (query.length < 3) {
                this.brideCitySuggestions = [];
                return;
            }

            this.brideCityDebounceTimer = setTimeout(() => {
                this.fetchBrideCitySuggestions(query);
            }, 350);
        },
        async fetchBrideCitySuggestions(query) {
            this.brideCityAbortController = new AbortController();

            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=jsonv2&addressdetails=1&limit=10&dedupe=0&q=${encodeURIComponent(query)}`,
                    {
                        signal: this.brideCityAbortController.signal,
                        headers: { 'Accept-Language': 'en' }
                    }
                );

                if (!response.ok) {
                    this.brideCitySuggestions = [];
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

                this.brideCitySuggestions = Array.from(uniqueByLocation.values()).slice(0, 6);
            } catch (error) {
                if (error.name !== 'AbortError') {
                    this.brideCitySuggestions = [];
                }
            } finally {
                this.brideCityAbortController = null;
            }
        },
        onBrideCityFocus() {
            if (this.brideCityBlurTimer) {
                clearTimeout(this.brideCityBlurTimer);
                this.brideCityBlurTimer = null;
            }
            this.showBrideCitySuggestions = this.brideCitySuggestions.length > 0;
        },
        onBrideCityBlur() {
            this.brideCityBlurTimer = setTimeout(() => {
                this.showBrideCitySuggestions = false;
            }, 120);
        },
        selectBrideCitySuggestion(selected) {
            this.form.bride.cityMunicipality = selected.cityMunicipality;
            if (!this.form.bride.province && selected.province) {
                this.form.bride.province = selected.province;
            }
            if (!this.form.bride.country && selected.country) {
                this.form.bride.country = selected.country;
            }
            this.showBrideCitySuggestions = false;
        },
        onBrideResidenceInput() {
            const query = (this.form.bride.residence || '').trim();
            this.showBrideResidenceSuggestions = true;

            if (this.brideResidenceDebounceTimer) {
                clearTimeout(this.brideResidenceDebounceTimer);
            }
            if (this.brideResidenceAbortController) {
                this.brideResidenceAbortController.abort();
                this.brideResidenceAbortController = null;
            }
            if (query.length < 5) {
                this.brideResidenceSuggestions = [];
                return;
            }

            this.brideResidenceDebounceTimer = setTimeout(() => {
                this.fetchBrideResidenceSuggestions(query);
            }, 350);
        },
        async fetchBrideResidenceSuggestions(query) {
            this.brideResidenceAbortController = new AbortController();

            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=jsonv2&addressdetails=1&limit=8&dedupe=0&q=${encodeURIComponent(query)}`,
                    {
                        signal: this.brideResidenceAbortController.signal,
                        headers: { 'Accept-Language': 'en' }
                    }
                );

                if (!response.ok) {
                    this.brideResidenceSuggestions = [];
                    return;
                }

                const data = await response.json();
                const suggestions = data
                    .map((item, index) => {
                        const address = item.address || {};
                        const countryCode = (address.country_code || '').toLowerCase();
                        const displayName = item.display_name || '';
                        const mainText =
                            item.name ||
                            address.road ||
                            address.suburb ||
                            address.city ||
                            address.town ||
                            address.municipality ||
                            displayName.split(',')[0] ||
                            '';

                        if (!displayName) return null;

                        let score = 0;
                        if (countryCode === 'ph') score += 30;
                        if (displayName.toLowerCase().startsWith(query.toLowerCase())) score += 10;

                        return {
                            id: item.place_id || `b-res-${index}`,
                            mainText,
                            displayName,
                            addressParts: this.getResidenceAddressParts(address, item.name || ''),
                            formattedAddress: this.formatResidenceAddress(address, displayName, item.name || ''),
                            formattedResidenceOnly: this.formatResidenceAddress(address, displayName, item.name || '', false),
                            score
                        };
                    })
                    .filter(Boolean)
                    .sort((a, b) => b.score - a.score);

                const uniqueByDisplay = new Map();
                suggestions.forEach((item) => {
                    if (!uniqueByDisplay.has(item.displayName.toLowerCase())) {
                        uniqueByDisplay.set(item.displayName.toLowerCase(), item);
                    }
                });

                this.brideResidenceSuggestions = Array.from(uniqueByDisplay.values()).slice(0, 6);
            } catch (error) {
                if (error.name !== 'AbortError') {
                    this.brideResidenceSuggestions = [];
                }
            } finally {
                this.brideResidenceAbortController = null;
            }
        },
        onBrideResidenceFocus() {
            if (this.brideResidenceBlurTimer) {
                clearTimeout(this.brideResidenceBlurTimer);
                this.brideResidenceBlurTimer = null;
            }
            this.showBrideResidenceSuggestions = this.brideResidenceSuggestions.length > 0;
        },
        onBrideResidenceBlur() {
            this.brideResidenceBlurTimer = setTimeout(() => {
                this.showBrideResidenceSuggestions = false;
            }, 120);
        },
        selectBrideResidenceSuggestion(selected) {
            const parts = selected.addressParts || {};
            if (!this.form.bride.housenum && parts.houseNumber) {
                this.form.bride.housenum = parts.houseNumber;
            }
            if (!this.form.bride.street && parts.street) {
                this.form.bride.street = parts.street;
            }
            this.form.bride.residence = selected.formattedResidenceOnly || selected.formattedAddress || selected.displayName;
            this.showBrideResidenceSuggestions = false;
        },
        goBack() {
            let prevStep = this.step - 1;


            this.step = prevStep;
            window.scrollTo(0, 0);
        },
        goNext() {
            // Clear any existing messages before starting new validation
            this.message = [];

            // Current form flow is 4 steps only:
            // 1 = Documents, 2 = Groom, 3 = Bride, 4 = Review/Submit
            if (this.totalSteps === 4) {
                if (this.step === 1) {
                    const baseDocs = ['cenomar', 'psa', 'govtIssuedId', 'pmocCertificate'];
                    const missingGroomBase = baseDocs.some((doc) => !this.form.groom.documents[doc]);
                    const missingBrideBase = baseDocs.some((doc) => !this.form.bride.documents[doc]);

                    if (missingGroomBase || missingBrideBase) {
                        this.message.push("Please upload all required base documents for both Groom and Bride.");
                        this.scrollToError();
                        return;
                    }

                    if (this.type === "groom" || this.type === "both") {
                        const missingGroomForeignBase = ['legalCapacity', 'validPassport']
                            .some((doc) => !this.form.groom.documents[doc]);
                        const groomStatus = (this.form.groom.civilStatus || '').toLowerCase();
                        const needsApostilled = groomStatus === 'widowed' && !this.form.groom.documents.apostilled;
                        const needsDivorceDecree = ['divorced', 'annulled'].includes(groomStatus) && !this.form.groom.documents.divorceDecree;
                        const missingGroomForeign = missingGroomForeignBase || needsApostilled || needsDivorceDecree;
                        if (missingGroomForeign) {
                            this.message.push("Please upload all required foreigner documents for Groom.");
                            this.scrollToError();
                            return;
                        }
                    }

                    if (this.type === "bride" || this.type === "both") {
                        const missingBrideForeign = ['legalCapacity', 'validPassport']
                            .some((doc) => !this.form.bride.documents[doc]);
                        if (missingBrideForeign) {
                            this.message.push("Please upload all required foreigner documents for Bride.");
                            this.scrollToError();
                            return;
                        }
                    }

                    if (this.groomRequirement === "parental-consent" && !this.form.groom.documents.parentalConsent) {
                        this.message.push("Please upload Groom parental consent document.");
                        this.scrollToError();
                        return;
                    }
                    if (this.groomRequirement === "parental-advise" && !this.form.groom.documents.parentalAdvise) {
                        this.message.push("Please upload Groom parental advice document.");
                        this.scrollToError();
                        return;
                    }
                    if (this.brideRequirement === "parental-consent" && !this.form.bride.documents.parentalConsent) {
                        this.message.push("Please upload Bride parental consent document.");
                        this.scrollToError();
                        return;
                    }
                    if (this.brideRequirement === "parental-advise" && !this.form.bride.documents.parentalAdvise) {
                        this.message.push("Please upload Bride parental advice document.");
                        this.scrollToError();
                        return;
                    }
                }

                if (this.step === 2) {
                    if (!this.form.groom.firstName || !this.form.groom.lastName) {
                        this.message.push("Please fill in Groom name fields.");
                        this.scrollToError();
                        return;
                    }
                    if (!this.form.groom.day || !this.form.groom.month || !this.form.groom.year) {
                        this.message.push("Please fill in Groom birth date.");
                        this.scrollToError();
                        return;
                    }
                    if (!this.form.groom.cityMunicipality || !this.form.groom.province || !this.form.groom.country) {
                        this.message.push("Please fill in Groom birth place.");
                        this.scrollToError();
                        return;
                    }
                    if (!this.form.groom.age || !this.form.groom.sex || !this.form.groom.citizenship) {
                        this.message.push("Please complete Groom personal info.");
                        this.scrollToError();
                        return;
                    }
                    if (
                        (!this.form.groom.streethousenum && !(this.form.groom.housenum && this.form.groom.street)) ||
                        !this.form.groom.residence
                    ) {
                        this.message.push("Please fill in full Groom residence.");
                        this.scrollToError();
                        return;
                    }
                    if (!this.form.groom.religion || !this.form.groom.civilStatus) {
                        this.message.push("Please fill in Groom religion and civil status.");
                        this.scrollToError();
                        return;
                    }
                    if (this.form.groom.civilStatus.toLowerCase() !== 'single') {
                        if (!this.form.groom.previousMarriageDissolve || !this.form.groom.dissolvedCityMunicipality) {
                            this.message.push("Please provide Groom dissolution details and place.");
                            this.scrollToError();
                            return;
                        }
                        if (!this.form.groom.dissolvedDay || !this.form.groom.dissolvedMonth || !this.form.groom.dissolvedYear) {
                            this.message.push("Please provide Groom dissolved date (day, month, year).");
                            this.scrollToError();
                            return;
                        }
                        if (!this.isValidFullDate(this.form.groom.dissolvedDay, this.form.groom.dissolvedMonth, this.form.groom.dissolvedYear)) {
                            this.message.push("Invalid Groom dissolved date.");
                            this.scrollToError();
                            return;
                        }
                    }
                    if (!this.form.groom.fatherFirstName || !this.form.groom.fatherLastName || !this.form.groom.fatherCitizenship) {
                        this.message.push("Please complete Groom father's information.");
                        this.scrollToError();
                        return;
                    }
                    if (this.form.groom.fatherCitizenship === 'Others' && !this.form.groom.fatherCitizenshipOther) {
                        this.message.push("Please specify Groom father's citizenship.");
                        this.scrollToError();
                        return;
                    }
                    if (!this.form.groom.fatherHouseNumStreet || !this.form.groom.fatherResidence) {
                        this.message.push("Please complete Groom father's residence.");
                        this.scrollToError();
                        return;
                    }
                    if (!this.form.groom.motherMaidenFirstName || !this.form.groom.motherMaidenLastName || !this.form.groom.motherMaidenCitizenship) {
                        this.message.push("Please complete Groom mother's information.");
                        this.scrollToError();
                        return;
                    }
                    if (this.form.groom.motherMaidenCitizenship === 'Others' && !this.form.groom.motherMaidenCitizenshipOther) {
                        this.message.push("Please specify Groom mother's citizenship.");
                        this.scrollToError();
                        return;
                    }
                    if (!this.form.groom.motherHouseNumStreet || !this.form.groom.motherMaidenResidence) {
                        this.message.push("Please complete Groom mother's residence.");
                        this.scrollToError();
                        return;
                    }
                    if (this.groomRequirement !== "no-need") {
                        if (!this.form.consentSource.groom.citizenship || !this.form.consentSource.groom.relationship) {
                            this.message.push("Please complete Groom consent/advice source info.");
                            this.scrollToError();
                            return;
                        }
                        if (this.form.consentSource.groom.citizenship === 'Others' && !this.form.consentSource.groom.citizenshipOther) {
                            this.message.push("Please specify Groom consent/advice source citizenship.");
                            this.scrollToError();
                            return;
                        }
                        if (!this.form.consentSource.groom.houseNumStreet || !this.form.consentSource.groom.residence) {
                            this.message.push("Please complete Groom consent/advice source residence.");
                            this.scrollToError();
                            return;
                        }
                    }
                }

                if (this.step === 3) {
                    if (!this.form.bride.firstName || !this.form.bride.lastName) {
                        this.message.push("Please fill in Bride name fields.");
                        this.scrollToError();
                        return;
                    }
                    if (!this.form.bride.day || !this.form.bride.month || !this.form.bride.year) {
                        this.message.push("Please fill in Bride birth date.");
                        this.scrollToError();
                        return;
                    }
                    if (!this.form.bride.cityMunicipality || !this.form.bride.province || !this.form.bride.country) {
                        this.message.push("Please fill in Bride birth place.");
                        this.scrollToError();
                        return;
                    }
                    if (!this.form.bride.age || !this.form.bride.sex || !this.form.bride.citizenship) {
                        this.message.push("Please complete Bride personal info.");
                        this.scrollToError();
                        return;
                    }
                    if (!this.form.bride.housenum || !this.form.bride.street || !this.form.bride.residence) {
                        this.message.push("Please fill in full Bride residence.");
                        this.scrollToError();
                        return;
                    }
                    if (!this.form.bride.religion || !this.form.bride.civilStatus) {
                        this.message.push("Please fill in Bride religion and civil status.");
                        this.scrollToError();
                        return;
                    }
                    if (this.form.bride.civilStatus.toLowerCase() !== 'single') {
                        if (!this.form.bride.previousMarriageDissolve || !this.form.bride.dissolvedCityMunicipality) {
                            this.message.push("Please provide Bride dissolution details and place.");
                            this.scrollToError();
                            return;
                        }
                        if (!this.form.bride.dissolvedDay || !this.form.bride.dissolvedMonth || !this.form.bride.dissolvedYear) {
                            this.message.push("Please provide Bride dissolved date (day, month, year).");
                            this.scrollToError();
                            return;
                        }
                        if (!this.isValidFullDate(this.form.bride.dissolvedDay, this.form.bride.dissolvedMonth, this.form.bride.dissolvedYear)) {
                            this.message.push("Invalid Bride dissolved date.");
                            this.scrollToError();
                            return;
                        }
                    }
                    if (!this.form.bride.fatherFirstName || !this.form.bride.fatherLastName || !this.form.bride.fatherCitizenship) {
                        this.message.push("Please complete Bride father's information.");
                        this.scrollToError();
                        return;
                    }
                    if (this.form.bride.fatherCitizenship === 'Others' && !this.form.bride.fatherCitizenshipOther) {
                        this.message.push("Please specify Bride father's citizenship.");
                        this.scrollToError();
                        return;
                    }
                    if (!this.form.bride.fatherHouseNumStreet || !this.form.bride.fatherResidence) {
                        this.message.push("Please complete Bride father's residence.");
                        this.scrollToError();
                        return;
                    }
                    if (!this.form.bride.motherMaidenFirstName || !this.form.bride.motherMaidenLastName || !this.form.bride.motherMaidenCitizenship) {
                        this.message.push("Please complete Bride mother's information.");
                        this.scrollToError();
                        return;
                    }
                    if (this.form.bride.motherMaidenCitizenship === 'Others' && !this.form.bride.motherMaidenCitizenshipOther) {
                        this.message.push("Please specify Bride mother's citizenship.");
                        this.scrollToError();
                        return;
                    }
                    if (!this.form.bride.motherHouseNumStreet || !this.form.bride.motherMaidenResidence) {
                        this.message.push("Please complete Bride mother's residence.");
                        this.scrollToError();
                        return;
                    }
                    if (this.brideRequirement !== "no-need") {
                        if (!this.form.consentSource.bride.citizenship || !this.form.consentSource.bride.relationship) {
                            this.message.push("Please complete Bride consent/advice source info.");
                            this.scrollToError();
                            return;
                        }
                        if (this.form.consentSource.bride.citizenship === 'Others' && !this.form.consentSource.bride.citizenshipOther) {
                            this.message.push("Please specify Bride consent/advice source citizenship.");
                            this.scrollToError();
                            return;
                        }
                        if (!this.form.consentSource.bride.houseNumStreet || !this.form.consentSource.bride.residence) {
                            this.message.push("Please complete Bride consent/advice source residence.");
                            this.scrollToError();
                            return;
                        }
                    }
                }

                if (this.message.length > 0) return;

                const nextStep = Math.min(this.step + 1, this.totalSteps);
                const progressData = {
                    step: nextStep,
                    groom: { ...this.form.groom, documents: {} },
                    bride: { ...this.form.bride, documents: {} },
                    consentSource: { ...this.form.consentSource },
                    isForeigner: this.isForeigner,
                    type: this.type,
                    groomRequirement: this.groomRequirement,
                    brideRequirement: this.brideRequirement
                };

                localStorage.setItem('marriage_form_progress', JSON.stringify(progressData));
                this.step = nextStep;
                window.scrollTo(0, 0);
                return;
            }

            let currentRequiredDocs = [];
            let isGroomMissing = false;
            let isBrideMissing = false;
            // Legacy validation map (kept for non-4-step flows)
            if (this.totalSteps !== 4) {
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
                        const foreignDocs = ['legalCapacity', 'validPassport'];

                        // Logic: Only validate these if the person is flagged as a foreigner
                        if (this.type === "groom" || this.type === "both") {
                            missingGroomForeign = foreignDocs.some(doc => !this.form.groom.documents[doc]);
                            const groomStatus = (this.form.groom.civilStatus || '').toLowerCase();
                            if (groomStatus === 'widowed' && !this.form.groom.documents.apostilled) {
                                missingGroomForeign = true;
                            }
                            if (['divorced', 'annulled'].includes(groomStatus) && !this.form.groom.documents.divorceDecree) {
                                missingGroomForeign = true;
                            }
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
                        if (
                            (!this.form.groom.streethousenum && !(this.form.groom.housenum && this.form.groom.street)) ||
                            !this.form.groom.residence
                        ) {
                            this.message.push("Please fill in full groom residence (House no./Street and Barangay to Zip code).");
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
                        if (this.form.groom.civilStatus && this.form.groom.civilStatus.toLowerCase() !== 'single') {
                            if (!this.form.groom.previousMarriageDissolve || !this.form.groom.dissolvedCityMunicipality) {
                                this.message.push("Please provide the dissolution details and place.");
                                this.scrollToError();
                                return;
                            }
                            if (!this.form.groom.dissolvedDay || !this.form.groom.dissolvedMonth || !this.form.groom.dissolvedYear) {
                                this.message.push("Please provide the complete dissolved date (day, month, year).");
                                this.scrollToError();
                                return;
                            }
                            if (!this.isValidFullDate(this.form.groom.dissolvedDay, this.form.groom.dissolvedMonth, this.form.groom.dissolvedYear)) {
                                this.message.push("Invalid dissolved date. Use a valid day, month name/number, and year.");
                                this.scrollToError();
                                return;
                            }
                        }
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
                        if (this.form.groom.fatherCitizenship === 'Others' && !this.form.groom.fatherCitizenshipOther) {
                            this.message.push("Please specify father's citizenship.");
                            this.scrollToError();
                            return;
                        }
                        // if (!this.form.groom.fatherMiddleName) this.form.groom.fatherMiddleName = "N/A";
                        break;

                    case 16: // Groom Father Residence
                        if (!this.form.groom.fatherHouseNumStreet || !this.form.groom.fatherResidence) {
                            this.message.push("Please fill in father's complete residence.");
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
                        if (this.form.groom.motherMaidenCitizenship === 'Others' && !this.form.groom.motherMaidenCitizenshipOther) {
                            this.message.push("Please specify mother's citizenship.");
                            this.scrollToError();
                            return;
                        }
                        // if (!this.form.groom.motherMaidenMiddleName) this.form.groom.motherMaidenMiddleName = "N/A";
                        break;

                    case 18: // Groom Mother Residence
                        if (!this.form.groom.motherHouseNumStreet || !this.form.groom.motherMaidenResidence) {
                            this.message.push("Please fill in mother's complete residence.");
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
                            if (this.form.consentSource.groom.citizenship === 'Others' && !this.form.consentSource.groom.citizenshipOther) {
                                this.message.push("Please specify consent/advice source citizenship.");
                                this.scrollToError();
                                return;
                            }
                        }
                        break;

                    case 20: // Groom consent/advice source residence
                        if (
                            this.groomRequirement !== "no-need" &&
                            (!this.form.consentSource.groom.houseNumStreet || !this.form.consentSource.groom.residence)
                        ) {
                            this.message.push("Please fill in groom consent/advice source complete residence.");
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
                        if (!this.form.bride.housenum || !this.form.bride.street || !this.form.bride.residence) {
                            this.message.push("Please fill in full bride residence (House no., Street, and Barangay to Zip code).");
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
                        if (this.form.bride.civilStatus && this.form.bride.civilStatus.toLowerCase() !== 'single') {
                            if (!this.form.bride.previousMarriageDissolve || !this.form.bride.dissolvedCityMunicipality) {
                                this.message.push("Please provide the dissolution details and place.");
                                this.scrollToError();
                                return;
                            }
                            if (!this.form.bride.dissolvedDay || !this.form.bride.dissolvedMonth || !this.form.bride.dissolvedYear) {
                                this.message.push("Please provide the complete dissolved date (day, month, year).");
                                this.scrollToError();
                                return;
                            }
                            if (!this.isValidFullDate(this.form.bride.dissolvedDay, this.form.bride.dissolvedMonth, this.form.bride.dissolvedYear)) {
                                this.message.push("Invalid dissolved date. Use a valid day, month name/number, and year.");
                                this.scrollToError();
                                return;
                            }
                        }
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
                        if (this.form.bride.fatherCitizenship === 'Others' && !this.form.bride.fatherCitizenshipOther) {
                            this.message.push("Please specify father's citizenship.");
                            this.scrollToError();
                            return;
                        }
                        // if (!this.form.bride.fatherMiddleName) this.form.bride.fatherMiddleName = "N/A";
                        break;

                    case 31: // Bride Father Residence
                        if (!this.form.bride.fatherHouseNumStreet || !this.form.bride.fatherResidence) {
                            this.message.push("Please fill in father's complete residence.");
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
                        if (this.form.bride.motherMaidenCitizenship === 'Others' && !this.form.bride.motherMaidenCitizenshipOther) {
                            this.message.push("Please specify mother's citizenship.");
                            this.scrollToError();
                            return;
                        }
                        // if (!this.form.bride.motherMaidenMiddleName) this.form.bride.motherMaidenMiddleName = "N/A";
                        break;

                    case 33: // Bride Mother Residence
                        if (!this.form.bride.motherHouseNumStreet || !this.form.bride.motherMaidenResidence) {
                            this.message.push("Please fill in mother's complete residence.");
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
                            if (this.form.consentSource.bride.citizenship === 'Others' && !this.form.consentSource.bride.citizenshipOther) {
                                this.message.push("Please specify bride consent/advice source citizenship.");
                                this.scrollToError();
                                return;
                            }
                        }
                        break;

                    case 35: // Bride consent/advice source residence
                        if (
                            this.brideRequirement !== "no-need" &&
                            (!this.form.consentSource.bride.houseNumStreet || !this.form.consentSource.bride.residence)
                        ) {
                            this.message.push("Please fill in bride consent/advice source complete residence.");
                            this.scrollToError();
                            return;
                        }
                        break;

                    default:
                        break;
                }
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
            const selectedRequirement = type === 'groom' ? this.groomRequirement : this.brideRequirement;
            const personLabel = type === 'groom' ? 'groom' : 'bride';

            if (!person.day || !person.month || !person.year) {
                person.age = '';
                this.setAgeError(type, '');
            } else if (!this.isValidCalendarDate(person.day, person.month, person.year)) {
                person.age = '';
                this.setAgeError(type, 'Please select a valid calendar date.');
            } else {
                const calculatedAge = this.calculateAge(person.day, person.month, person.year);
                person.age = calculatedAge;

                const ageError = this.getAgeRangeValidationError(calculatedAge, selectedRequirement, personLabel);
                this.setAgeError(type, ageError || '');
            }

            // For previously married logic
            const groomMarried = this.form.groom.civilStatus && this.form.groom.civilStatus.toLowerCase() !== 'single';
            const brideMarried = this.form.bride.civilStatus && this.form.bride.civilStatus.toLowerCase() !== 'single';
            this.isPreviouslyMarried = !!(groomMarried || brideMarried);
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
        if (this.groomResidenceDebounceTimer) {
            clearTimeout(this.groomResidenceDebounceTimer);
        }
        if (this.groomResidenceBlurTimer) {
            clearTimeout(this.groomResidenceBlurTimer);
        }
        if (this.groomResidenceAbortController) {
            this.groomResidenceAbortController.abort();
        }
        if (this.groomRelatedResidenceDebounceTimer) {
            clearTimeout(this.groomRelatedResidenceDebounceTimer);
        }
        if (this.groomRelatedResidenceBlurTimer) {
            clearTimeout(this.groomRelatedResidenceBlurTimer);
        }
        if (this.groomRelatedResidenceAbortController) {
            this.groomRelatedResidenceAbortController.abort();
        }
        if (this.groomDissolvedPlaceDebounceTimer) {
            clearTimeout(this.groomDissolvedPlaceDebounceTimer);
        }
        if (this.groomDissolvedPlaceBlurTimer) {
            clearTimeout(this.groomDissolvedPlaceBlurTimer);
        }
        if (this.groomDissolvedPlaceAbortController) {
            this.groomDissolvedPlaceAbortController.abort();
        }
        if (this.brideRelatedResidenceDebounceTimer) {
            clearTimeout(this.brideRelatedResidenceDebounceTimer);
        }
        if (this.brideRelatedResidenceBlurTimer) {
            clearTimeout(this.brideRelatedResidenceBlurTimer);
        }
        if (this.brideRelatedResidenceAbortController) {
            this.brideRelatedResidenceAbortController.abort();
        }
        if (this.brideResidenceDebounceTimer) {
            clearTimeout(this.brideResidenceDebounceTimer);
        }
        if (this.brideResidenceBlurTimer) {
            clearTimeout(this.brideResidenceBlurTimer);
        }
        if (this.brideResidenceAbortController) {
            this.brideResidenceAbortController.abort();
        }
        if (this.brideCityDebounceTimer) {
            clearTimeout(this.brideCityDebounceTimer);
        }
        if (this.brideCityBlurTimer) {
            clearTimeout(this.brideCityBlurTimer);
        }
        if (this.brideCityAbortController) {
            this.brideCityAbortController.abort();
        }
        if (this.brideDissolvedPlaceDebounceTimer) {
            clearTimeout(this.brideDissolvedPlaceDebounceTimer);
        }
        if (this.brideDissolvedPlaceAbortController) {
            this.brideDissolvedPlaceAbortController.abort();
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
    background: rgba(255, 255, 255, 0.1) !important;
    /* Highly transparent */
    backdrop-filter: blur(20px) saturate(160%);
    -webkit-backdrop-filter: blur(20px) saturate(160%);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 24px;
}

.card-header {
    background: rgba(30, 60, 114, 0.5) !important;
    /* Darker tinted top */
    border-radius: 24px 24px 0 0 !important;
}

/* TYPOGRAPHY */
.section-title {
    color: #ffffff;
    font-weight: 700;
    border-left: 4px solid #0d6efd;
    padding-left: 15px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
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
    background: rgba(255, 255, 255, 0.9) !important;
    /* Almost white for readability */
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
    max-height: 100px;
    /* Reduced from 180px */
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.glass-placeholder {
    min-height: 120px !important;
    /* Smaller footprint */
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
