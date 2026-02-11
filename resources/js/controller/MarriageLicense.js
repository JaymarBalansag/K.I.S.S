import api from "./api";


export async function submitMarriageLicenseApplication(formData) {
    const response = await api.post('/submit/marriage-license-application', formData);
    return response;
}

// export async function getApplicants(){
//     const response = await api.get("/applications");
//     return response;
// }

export async function viewApplicants(application_id, control_number) {
    const response = await api.get(`/view/applicants/${application_id}/${control_number}`)
    return response;
}

export async function getApplicants(status = 'all', search = '', page = 1, order = 'desc'){
    try {
        // Construct the URL with query parameters
        // This hits your: /api/applications/all?search=Ricardo&page=1
        const response = await api.get(`/applications/${status}/${order}`, {
            params: {
                search: search,
                page: page
            }
        });
        console.log(response)
        return response;
    } catch (error) {
        console.error("Error fetching applicants:", error);
        throw error;
    }
};


