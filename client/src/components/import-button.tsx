import { useEffect, useRef } from "react";
import { Button } from "./ui/button";
import { useForm } from "react-hook-form";
import { useImportPDF } from "@/api/mutations";

interface ImportProps {
  pdf: FileList | null;
}

const ImportButton = () => {
  const { mutate: importPDF } = useImportPDF();
  const form = useForm<ImportProps>({
    defaultValues: {
      pdf: null,
    },
  });

  const btn = useRef<HTMLButtonElement>(null);

  useEffect(() => {
    if (form.getValues("pdf")) {
      btn.current?.click();
    }
  }, [form.watch("pdf")]);

  return (
    <form
      encType="multipart-formdata"
      className="relative grid place-items-center overflow-hidden h-fit"
      onSubmit={form.handleSubmit((data) => {
        const formData = new FormData();

        formData.set("pdf", data.pdf![0]);

        importPDF(formData);
      })}
    >
      <input
        className="absolute z-99 text-transparent bg-transparent py-2"
        type="file"
        accept="application/pdf"
        {...form.register("pdf")}
      />
      <Button ref={btn} variant={"outline"}>
        Import
      </Button>
    </form>
  );
};

export default ImportButton;
