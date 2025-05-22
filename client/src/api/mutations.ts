import { useMutation } from "@tanstack/react-query";
import { importPDF } from "./services";
import { queryClient } from "@/main";

export const useImportPDF = () => {
  const client = queryClient;

  return useMutation({
    mutationKey: ["voters"],
    mutationFn: async (formData: FormData) => importPDF(formData),

    onSuccess: (data) => {
      client.invalidateQueries({
        queryKey: ["voters"],
      });

      console.log(data);
    },
  });
};
