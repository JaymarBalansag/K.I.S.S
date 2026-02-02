    <template>
    <div class="main-container min-vh-100 d-flex flex-column">
        
        <Navbar></Navbar>

        <main class="flex-grow-1 py-5">
        <div class="container py-5 mt-5">
            
            <div class="row justify-content-center text-center mb-5 mt-4">
            <div class="col-lg-9 col-xl-8">
                <span class="badge bg-primary bg-opacity-75 rounded-pill px-4 py-2 mb-3 shadow-sm text-uppercase fw-bold" style="letter-spacing: 1px;">
                Common Inquiries
                </span>
                <h1 class="display-2 fw-bolder text-white mb-3 text-shadow-heavy">
                Frequently Asked Questions
                </h1>
                <p class="lead text-white fw-medium mb-4 text-shadow-medium opacity-90 mx-auto" style="max-width: 700px;">
                Find quick answers to common questions about marriage registration, license requirements, and our digital services.
                </p>
            </div>
            </div>

            <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="card border-0 shadow-lg glass-card p-2 p-md-4">
                <div class="card-body">
                    <div class="accordion accordion-flush custom-accordion" id="faqAccordion">
                    
                    <div v-for="(faq, index) in faqs" :key="index" class="accordion-item bg-transparent border-bottom border-dark border-opacity-10">
                        <h2 class="accordion-header" :id="'heading' + index">
                        <button 
                            class="accordion-button collapsed bg-transparent fw-bold text-dark py-4 px-2 shadow-none" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            :data-bs-target="'#collapse' + index" 
                            aria-expanded="false" 
                            :aria-controls="'collapse' + index"
                        >
                            {{ faq.question }}
                        </button>
                        </h2>
                        <div 
                        :id="'collapse' + index" 
                        class="accordion-collapse collapse" 
                        :aria-labelledby="'heading' + index" 
                        data-bs-parent="#faqAccordion"
                        >
                        <div class="accordion-body text-muted lh-lg px-2 pb-4">
                            {{ faq.answer }}
                        </div>
                        </div>
                    </div>

                    </div>
                </div>
                </div>

                <div class="text-center mt-5">
                <p class="text-white-50">Still have questions?</p>
                <RouterLink to="/contact" class="btn btn-outline-light rounded-pill px-4 fw-bold shadow-sm">
                    Contact Support Team
                </RouterLink>
                </div>
            </div>
            </div>
        </div>
        </main>

        <Footer></Footer>
    </div>
    </template>

    <script>
    import Navbar from '../../components/navbar.vue';
    import Footer from '../../components/footer.vue';
    export default {
    name: 'FAQPage',
    components: {
        Navbar,
        Footer
    },
    data() {
        return {
        config: {
            siteName: "LCRO Abuyog"
        },
        faqs: [
            {
            question: "How long does it take to process a marriage license?",
            answer: "By law, there is a mandatory 10-day publication period before a marriage license can be issued. This period begins once your application is fully submitted and verified at our office."
            },
            {
            question: "What are the basic requirements for a marriage license?",
            answer: "Basic requirements typically include PSA Birth Certificates, Certificate of No Marriage (CENOMAR), valid IDs, and Attendance in Pre-Marriage Orientation and Counseling. Additional documents may be required based on age or previous marital status."
            },
            {
            question: "What is the validity period of a marriage license?",
            answer: "A marriage license is valid for 120 days from the date of issuance and can be used anywhere in the Philippines. If the 120-day period expires without being used, you must apply for a new license."
            },
            {
            question: "Can I request a certified copy of my marriage certificate through this portal?",
            answer: "Currently, you can initiate the application and submit requirements via the portal. For the final certified copy, you may need to visit the office for verification or choose our courier delivery option if available in your area."
            }
        ]
        };
    }
    };
    </script>

    <style scoped>
    .main-container {
    font-family: 'Inter', sans-serif;
    background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), 
                url('../../../../public/background.jpg'); 
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    }

    .text-shadow-heavy {
    text-shadow: 0 4px 15px rgba(0, 0, 0, 0.9);
    }

    .glass-card {
    background: rgba(255, 255, 255, 0.96) !important;
    backdrop-filter: blur(12px);
    border-radius: 20px;
    }

    /* Custom Accordion Styling */
    .custom-accordion .accordion-button:not(.collapsed) {
    color: #0d6efd;
    background-color: transparent;
    }

    .custom-accordion .accordion-button::after {
    filter: brightness(0); /* Makes the arrow visible if needed */
    }

    /* If the background is too dark, adjust arrow color */
    .navbar-dark .navbar-toggler-icon {
    filter: invert(1);
    }
    </style>