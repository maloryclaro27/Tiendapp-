import type { Brand, Product } from "@/lib/api";

export type StorefrontBrand = Brand;

export type StorefrontProduct = Product;

export type SocialLink = {
  label: string;
  href: string;
  iconSrc: string;
  fallback: string;
};

export function slugify(value: string) {
  return value
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/(^-|-$)/g, "");
}

export function getBrandImageSrc(brandName: string) {
  return `/catalog/brands/${slugify(brandName)}.png`;
}

export function getProductImageSrc(productName: string) {
  return `/catalog/products/${slugify(productName)}.png`;
}

export const socialLinks: SocialLink[] = [
  {
    label: "LinkedIn",
    href: "https://www.linkedin.com/company/tiendapp-s.a.s/?originalSubdomain=co",
    iconSrc: "/catalog/social/linkedin.png",
    fallback: "in",
  },
  {
    label: "Instagram",
    href: "https://www.instagram.com/tiendapp.latam/?hl=es",
    iconSrc: "/catalog/social/instagram.png",
    fallback: "IG",
  },
  {
    label: "Facebook",
    href: "https://www.facebook.com/tiendapp.latam/",
    iconSrc: "/catalog/social/facebook.png",
    fallback: "f",
  },
  {
    label: "YouTube",
    href: "https://www.youtube.com/c/TiendAPP/videos",
    iconSrc: "/catalog/social/youtube.png",
    fallback: "YT",
  },
];
