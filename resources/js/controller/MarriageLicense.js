import api from "./api";


export async function submitMarriageLicenseApplication(formData) {
    const response = await api.post('/submit/marriage-license-application', formData);
    return response;
}

export async function getApplicants(){
    const response = await api.get("/applications");
    return response;
}

export async function viewApplicants(application_id, control_number) {
    const response = await api.get(`view/applicants/${application_id}/${control_number}`)
    return response;
}

