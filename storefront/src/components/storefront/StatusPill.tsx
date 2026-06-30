import { AlertTriangle, CheckCircle2, XCircle } from "lucide-react";

import type { Product } from "@/lib/api";
import { cn } from "@/lib/utils";

type InventoryStatus = Product["inventory_status"];

const statusStyles = {
  "Stock saludable": "bg-emerald-50 text-emerald-700 ring-emerald-200",
  "Bajo stock": "bg-amber-50 text-amber-700 ring-amber-200",
  "Sin stock": "bg-rose-50 text-rose-700 ring-rose-200",
} satisfies Record<InventoryStatus, string>;

export function StatusPill({ status }: { status: InventoryStatus }) {
  const Icon =
    status === "Stock saludable"
      ? CheckCircle2
      : status === "Bajo stock"
        ? AlertTriangle
        : XCircle;

  return (
    <span
      className={cn(
        "inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-bold ring-1",
        statusStyles[status],
      )}
    >
      <Icon className="h-4 w-4" />
      {status}
    </span>
  );
}
