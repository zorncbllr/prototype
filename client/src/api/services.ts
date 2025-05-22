import { axiosInstance } from "@/lib/api";
import type { SuccessResponse, Voter } from "@/types/types";

export const getAllVoters = async (): Promise<Voter[]> => {
  return (await axiosInstance.get<Voter[]>("/voters")).data;
};

export const importPDF = async (
  formData: FormData
): Promise<SuccessResponse> => {
  return (
    await axiosInstance.post<SuccessResponse>("/voters/import", formData, {
      headers: {
        "Content-Type": "multipart-formdata",
      },
    })
  ).data;
};
