import api from "./api";

export async function login(email, password) {
    const res = await api.post("/login", { email, password });
    
    return res;
}

export async function logout() {
  await api.post("/logout");
  localStorage.removeItem("access_token");
  localStorage.removeItem("userInfo");
}