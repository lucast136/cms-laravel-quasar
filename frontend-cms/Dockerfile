# ==============================================================================
# 1. Base Stage: Dependencias base con npm ci reproducible
# ==============================================================================
FROM node:24-alpine AS base

WORKDIR /app

# Instalar dependencias (ignorando scripts de postinstall que requieren la configuración)
COPY package*.json ./
RUN npm ci --ignore-scripts

# ==============================================================================
# 2. Dev Stage: Para desarrollo local con hot-reload (quasar dev -m ssr)
# ==============================================================================
FROM base AS dev

COPY . .
RUN npx quasar prepare --silent

ENV HOST=0.0.0.0
ENV PORT=3000

EXPOSE 3000
CMD ["npm", "run", "dev"]

# ==============================================================================
# 3. Builder Stage: Compila la aplicación en modo SSR
# ==============================================================================
FROM base AS builder

# Copiar el resto del código fuente
COPY . .

# Variables de entorno en tiempo de compilación para Quasar / Vite
ARG VITE_API_URL=http://localhost:8000/api/v1
ENV VITE_API_URL=$VITE_API_URL

# Compilar proyecto en modo SSR (genera carpeta dist/ssr)
RUN npm run build

# ==============================================================================
# 4. Production Runner Stage (por defecto): Imagen ligera para servir SSR
# ==============================================================================
FROM node:24-alpine AS prod

WORKDIR /app

# Copiar los artefactos generados por Quasar SSR
COPY --from=builder /app/dist/ssr ./

# Instalar únicamente dependencias de producción para el servidor SSR
RUN npm install --omit=dev

# Variables de entorno de ejecución
ENV NODE_ENV=production
ENV HOST=0.0.0.0
ENV PORT=3000

EXPOSE 3000

# Iniciar servidor SSR de Quasar (Express)
CMD ["node", "index.js"]
